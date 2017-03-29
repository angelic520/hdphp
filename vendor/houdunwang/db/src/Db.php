<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\db;
use houdunwang\config\Config;

/**
 * Class Db
 * @package houdunwang\db
 */
class Db {
	//连接
	protected $link;

	//更改缓存驱动
	protected function driver() {
		$this->config();
		$this->link = new Query();
		$this->link->connection();

		return $this;
	}

	protected function config() {
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

	public function __call( $method, $params ) {
		if ( is_null( $this->link ) ) {
			$this->driver();
		}

		return call_user_func_array( [ $this->link, $method ], $params );
	}

	public static function __callStatic( $name, $arguments ) {
		return call_user_func_array( [ new static(), $name ], $arguments );
	}
}