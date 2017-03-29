<?php namespace houdunwang\framework\build;

use houdunwang\config\Config;

class Dispose {
	//初始化配置
	public static function bootstrap() {
		//加载服务配置项
		$servers = require __DIR__ . '/service.php';
		//加载配置文件
		Config::loadFiles( ROOT_PATH . '/system/config' );
		Config::set( 'service.providers', array_merge( $servers['providers'], c( 'service.providers' ) ) );
		Config::set( 'service.facades', array_merge( $servers['facades'], c( 'service.facades' ) ) );
		//设置时区
		date_default_timezone_set( Config::get( 'app.timezone' ) );
	}
}