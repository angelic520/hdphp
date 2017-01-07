<?php namespace houdunwang\framework\middleware;
/**
 * 全局中间件
 * Class App
 * @package hdphp\middleware\build
 */
class Validate {
	//执行中间件
	public function run() {
		//分配表单验证数据
		View::with( 'errors', Session::get( 'errors' ) ?: [ ] );
		Session::del( 'errors' );
	}
}