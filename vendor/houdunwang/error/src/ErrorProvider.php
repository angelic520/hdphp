<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\error;

use houdunwang\framework\build\Provider;

class ErrorProvider extends Provider {
	//延迟加载
	public $defer = false;

	public function boot() {
		Config::set( 'error.debug', Config::get( 'app.debug' ) );
		Error::bootstrap();
	}

	public function register() {
		$this->app->single( 'Error', function () {
			return new Error();
		} );
	}
}