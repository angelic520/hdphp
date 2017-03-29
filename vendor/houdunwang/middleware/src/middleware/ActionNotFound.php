<?php namespace houdunwang\middleware\middleware;

/**
 * 控制器方法不存在时执行的中间件
 * Class ActionNotFound
 * @package houdunwang\middleware\middleware
 */
class ActionNotFound {
	public function run() {
		_404();
	}
}