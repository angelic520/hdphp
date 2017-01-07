<?php namespace houdunwang\framework\middleware;

/**
 * 控制器不存在时执行的中间件
 * Class ActionNotFound
 * @package houdunwang\framework\middleware
 */
class ControllerNotFound {
	public function run() {
		_404();
	}
}