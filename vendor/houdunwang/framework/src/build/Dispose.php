<?php namespace houdunwang\framework\build;

use houdunwang\config\Config;

class Dispose {
	protected static $component = [
		'app',
		'error',
		'database',
		'crypt',
		'cookie',
		'view',
		'controller',
		'route',
		'middleware'
	];

	//初始化配置
	public static function bootstrap() {
		//加载服务配置项
		$servers = require __DIR__ . '/service.php';
		//加载配置文件
		Config::loadFiles( ROOT_PATH . '/system/config' );
		Config::set( 'service.providers', array_merge( c( 'service.providers' ), $servers['providers'] ) );
		Config::set( 'service.facades', array_merge( c( 'service.facades' ), $servers['facades'] ) );
		//针对组件定义配置
		foreach ( self::$component as $method ) {
			call_user_func_array( [ __CLASS__, $method ], [ ] );
		}

	}

	protected static function app() {
		//设置时区
		date_default_timezone_set( Config::get( 'app.timezone' ) );
	}

	protected static function error() {
		Config::set( 'error.debug', Config::get( 'app.debug' ) );
	}

	//设置数据库连接配置
	protected static function database() {
		//加载.env配置
		if ( is_file( '.env' ) ) {
			$config = [ ];
			foreach ( file( '.env' ) as $file ) {
				$data = explode( '=', $file );
				if ( count( $data ) == 2 ) {
					$config[ trim( $data[0] ) ] = trim( $data[1] );
				}
			}
			Config::set( 'database.host', $config['DB_HOST'] );
			Config::set( 'database.user', $config['DB_USER'] );
			Config::set( 'database.password', $config['DB_PASSWORD'] );
			Config::set( 'database.database', $config['DB_DATABASE'] );
		}
		//将公共数据库配置合并到 write 与 read 中
		$config = Config::getExtName( 'database', [ 'write', 'read' ] );
		if ( empty( $config['write'] ) ) {
			$config['write'][] = Config::getExtName( 'database', [
				'write',
				'read'
			] );
		}
		if ( empty( $config['read'] ) ) {
			$config['read'][] = Config::getExtName( 'database', [
				'write',
				'read'
			] );
		}
		//重设配置
		Config::set( 'database', $config );
		//缓存表字段
		Config::set( 'database.cache_field', ! Config::get( 'app.debug' ) );
	}

	//定义组件配置
	protected static function crypt() {
		Config::set( 'crypt.key', Config::get( 'app.key' ) );
	}

	protected static function cookie() {
		Config::set( 'cookie.key', Config::get( 'app.key' ) );
	}

	protected static function view() {
		Config::set( 'view.compile_open', Config::get( 'app.debug' ) );
	}

	protected static function controller() {
		Config::set( 'controller.app', Config::get( 'app.path' ) );
	}

	protected static function route() {
		Config::set( 'route.cache', Config::get( 'http.route_cache' ) );
	}

	protected static function middleware() {
		//添加全局中间件
		$config = array_merge( c( 'middleware.global' ), [
			'houdunwang\framework\middleware\Validate',
			'houdunwang\framework\middleware\Csrf'
		] );
		c( 'middleware.global', array_unique( $config ) );

		//控制器访问时控制器或方法不存在时执行的中间件
		c('middleware.web.CONTROLLER_NOT_FOUND',['houdunwang\framework\middleware\ControllerNotFound']);
		c('middleware.web.ACTION_NOT_FOUND',['houdunwang\framework\middleware\ActionNotFound']);
	}
}