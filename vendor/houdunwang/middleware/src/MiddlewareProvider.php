<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\middleware;

use houdunwang\config\Config;
use houdunwang\framework\build\Provider;

class MiddlewareProvider extends Provider {
	//延迟加载
	public $defer = false;

	public function boot() {
		//执行中间件
		Middleware::globals();
		Middleware::system( 'csrf_validate' );
		Middleware::system( 'form_validate' );
	}

	public function register() {
		//控制器访问时控制器或方法不存在时执行的中间件
		Config::set( 'middleware.system.controller_not_found', [ 'houdunwang\middleware\middleware\ControllerNotFound' ] );
		Config::set( 'middleware.system.action_not_found', [ 'houdunwang\middleware\middleware\ActionNotFound' ] );
		//路由规则没有匹配时执行
		Config::set( 'middleware.system.router_not_found', [ 'houdunwang\middleware\middleware\RouterNotFound' ] );
		//csrf表单令牌验证
		Config::set( 'middleware.system.csrf_validate', [ 'houdunwang\middleware\middleware\Csrf' ] );
		//分配表单验证失败信息
		Config::set( 'middleware.system.form_validate', [ 'houdunwang\middleware\middleware\Validate' ] );
		$this->app->single( 'Middleware', function () {
			return Middleware::single();
		} );

	}
}