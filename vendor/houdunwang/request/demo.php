<?php
require 'vendor/autoload.php';
$config = [
	//引擎:file,mysql,memcache,redis
	'driver'    => 'file',
	//session_name
	'name'      => 'hdcmsid',
	//cookie加密密钥
	'secureKey' => 'houdunwang88',
	//有效域名
	'domain'    => '',
	//过期时间 0 会话时间 3600 为一小时
	'expire'    => 0,
	#File
	'file'      => [
		'path' => 'storage/session',
	]
];
\houdunwang\config\Config::set( 'session', $config );
$config = [
	//密钥
	'key'    => '405305c793179059f8fd52436876750c587d19ccfbbe2a643743d021dbdcd79c',
	//前缀
	'prefix' => 'HOUDUNWANG##'
];
\houdunwang\config\Config::set( 'cookie', $config );
//$obj = new \houdunwang\request\Request();
//echo $obj->ip();
echo \houdunwang\request\Request::ip();