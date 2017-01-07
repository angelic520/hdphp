<?php
require 'vendor/autoload.php';
$config = [
	//引擎:file,mysql,memcache,redis
	'driver' => 'file',
	//session_name
	'name'   => 'hdcmsid',
	//有效域名
	'domain' => '',
	//过期时间 0 会话时间 3600 为一小时
	'expire' => 0,
	#File
	'file'   => [
		'path' => 'storage/session',
	],
];
\houdunwang\config\Config::set( 'session', $config );
\houdunwang\code\Code::make();