<?php
require 'vendor/autoload.php';
$config = [
	//密钥
	'key'    => '405305c793179059f8fd52436876750c587d19ccfbbe2a643743d021dbdcd79c',
	//前缀
	'prefix' => 'HOUDUNWANG##'
];
\houdunwang\config\Config::set( 'cookie', $config );
\houdunwang\cookie\Cookie::set( 'a2', 33 );
echo \houdunwang\cookie\Cookie::get( 'a2' );