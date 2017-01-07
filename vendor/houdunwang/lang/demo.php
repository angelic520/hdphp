<?php
require 'vendor/autoload.php';
$config = [
	//语言包
	'file' => ROOT_PATH . '/system/zh.php',
];
\houdunwang\config\Config::set( 'lang', $config );
echo \houdunwang\lang\Lang::get( 'name' );