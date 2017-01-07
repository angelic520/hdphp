<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\controller\build;

use Exception;
use houdunwang\config\Config;
use houdunwang\container\Container;
use houdunwang\middleware\Middleware;
use houdunwang\request\Request;
use houdunwang\response\Response;
use ReflectionMethod;

//控制器处理类
class Base {
	//路由参数
	protected $routeArgs = [ ];

	public function run( $routeArgs = [ ] ) {
		$this->routeArgs = $routeArgs;
		//URL结构处理
		$param = array_filter( explode( '/', Request::get( Config::get( 'http.url_var' ) ) ) );
		switch ( count( $param ) ) {
			case 2:
				array_unshift( $param, Config::get( 'http.default_module' ) );
				break;
			case 1:
				array_unshift( $param, Config::get( 'http.default_controller' ) );
				array_unshift( $param, Config::get( 'http.default_module' ) );
				break;
			case 0:
				array_unshift( $param, Config::get( 'http.default_action' ) );
				array_unshift( $param, Config::get( 'http.default_controller' ) );
				array_unshift( $param, Config::get( 'http.default_module' ) );
				break;
		}
		Request::set( 'get.' . Config::get( 'http.url_var' ), implode( '/', $param ) );
		$param[1] = preg_replace_callback( '/_([a-z])/', function ( $matches ) {
			return ucfirst( $matches[1] );
		}, $param[1] );
		define( 'MODULE', $param[0] );
		define( 'CONTROLLER', ucfirst( $param[1] ) );
		define( 'ACTION', $param[2] );
		define( 'MODULE_PATH', ROOT_PATH . '/' . Config::get( 'controller.app' ) . '/' . MODULE );
		define( 'VIEW_PATH', MODULE_PATH . '/view' );
		define( '__VIEW__', __ROOT__ . '/' . Config::get( 'controller.app' ) . '/' . MODULE . '/view' );
		$this->action();
	}

	//执行动作
	private function action() {
		//禁止使用模块检测
		if ( in_array( MODULE, Config::get( 'http.deny_module' ) ) ) {
			throw new Exception( "模块禁止访问" );
		}
		$class = Config::get( 'controller.app' ) . '\\' . MODULE . '\\controller\\' . CONTROLLER;

		//控制器不存在执行中间件
		if ( ! class_exists( $class ) ) {
			Middleware::exe('CONTROLLER_NOT_FOUND');
		}

		//方法不存在时执行中间件
		if(!method_exists($class,ACTION)){
			Middleware::exe('ACTION_NOT_FOUND');
		}

		//控制器开始运行中间件
		Middleware::exe( 'controller_begin' );
		$controller = Container::make( $class, true );

		//执行控制器中间件
		Middleware::controller();
		//执行动作
		try {
			/**
			 * 参数处理
			 * 控制器路由方式访问时解析路由参数并注入到控制器方法参数中
			 */
			//反射方法实例
			$reflectionMethod = new \ReflectionMethod( $class, ACTION );
			$args             = [ ];
			foreach ( $reflectionMethod->getParameters() as $k => $p ) {
				if ( isset( $this->routeArgs[ $p->name ] ) ) {
					//如果GET变量中存在则将GET变量值赋予,也就是说GET优先级高
					$args[ $p->name ] = $this->routeArgs[ $p->name ];
				} else {
					//如果类型为类时分析类
					if ( $dependency = $p->getClass() ) {
						$args[ $p->name ] = Container::build( $dependency->name );
					} else {
						//普通参数时获取默认值
						$args[ $p->name ] = Container::resolveNonClass( $p );
					}
				}
			}
			//执行控制器方法
			$result = $reflectionMethod->invokeArgs( $controller, $args );
			if ( IS_AJAX && is_array( $result ) ) {
				Response::ajax( $result );
			} else {
				echo( $result );
			}
		} catch ( ReflectionException $e ) {
			$action = new ReflectionMethod( $controller, '__call' );
			$action->invokeArgs( $controller, [ ACTION, '' ] );
		}
	}
}