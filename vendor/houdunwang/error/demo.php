<?php
require 'vendor/autoload.php';
$config = [
	'dir' => 'log'
];
\houdunwang\config\Config::set( 'log', $config );
$config = [
	//开启时直接显示错误信息
	'debug'       => true,
	//Notice类型错误显示
	'show_notice' => true,
	//错误提示页面
	'bug'         => 'resource/bug.php',
];
\houdunwang\config\Config::set( 'error', $config );
\houdunwang\error\Error::bootstrap();
ECHO A;
//throw new Exception('abc');