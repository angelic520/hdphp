<?php
/**
 * 请求参数
 *
 * @param $var 变量名
 * @param null $default 默认值
 * @param string $filter 数据处理函数
 *
 * @return mixed
 */
if ( ! function_exists( 'q' ) ) {
	/**
	 * 取得或设置全局数据包括:
	 * $_COOKIE,$_SESSION,$_GET,$_POST,$_REQUEST,$_SERVER,$_GLOBALS
	 *
	 * @param string $var 变量名
	 * @param mixed $default 默认值
	 * @param string $methods 函数库
	 *
	 * @return mixed
	 */
	function q( $var, $default = null, $methods = '' ) {
		return \houdunwang\request\Request::query( $var, $default, $methods );
	}
}