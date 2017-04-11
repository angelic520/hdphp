<?php
/**
 * 执行控制器方法
 * action('admin.user.add',1,3,4)
 * 从第2个参数开始为方法参数
 */
function action() {
	$args  = func_get_args();
	$info  = explode( '.', array_shift( $args ) );
	$class = Config::get( 'controller.app' ) . '\\' . $info[0] . '\\controller\\' . ucfirst( $info[1] );
	$res   = call_user_func_array( [ new $class, $info[2] ], $args );
	echo $res->toString();
	die;
}