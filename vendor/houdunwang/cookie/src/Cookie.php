<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\cookie;

use houdunwang\arr\Arr;
use houdunwang\config\Config;
use houdunwang\cookie\build\Base;

/**
 * Cookie 管理组件
 * Class Cookie
 * @package hdphp\cookie
 */
class Cookie {
	protected $link;

	//获取实例
	protected function driver() {
		$this->link = new Base();
		$this->link->config( Config::get( 'cookie' ) );
		$this->link->bootstrap();

		return $this;
	}

	public function __call( $method, $params ) {
		if ( is_null( $this->link ) ) {
			$this->driver();
		}

		return call_user_func_array( [ $this->link, $method ], $params );
	}

	public static function single() {
		static $link;
		if ( is_null( $link ) ) {
			$link = new static();
		}

		return $link;
	}

	public static function __callStatic( $name, $arguments ) {
		return call_user_func_array( [ static::single(), $name ], $arguments );
	}
}