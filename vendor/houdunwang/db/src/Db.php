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

use houdunwang\arr\Arr;
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
		$this->link = new Query();
		$this->link->config( Config::get( 'database' ) );
		$this->link->connection();

		return $this;
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