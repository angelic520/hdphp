<?php namespace houdunwang\middleware\middleware;
/**
 * 表单验证中间件
 * Class Validate
 * @package houdunwang\middleware\middleware
 */
class Validate {
	//执行中间件
	public function run() {
		//分配表单验证数据
		View::with( 'errors', Session::get( 'errors' ) ?: [ ] );
		Session::del( 'errors' );
	}
}