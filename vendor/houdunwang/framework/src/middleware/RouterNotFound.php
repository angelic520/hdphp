<?php namespace houdunwang\framework\middleware;

/**
 * 控制器访问条件不满足并且没有匹配路由时
 * Class RouterNotFound
 * @package houdunwang\framework\middleware
 */
class RouterNotFound {
	public function run() {
		_404();
	}
}