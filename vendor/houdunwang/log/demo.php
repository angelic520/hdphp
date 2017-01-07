<?php
require 'vendor/autoload.php';
$config = [
	'dir' => 'log'
];
\houdunwang\config\Config::set( 'log', $config );
\houdunwang\log\Log::write( '系统错误', \houdunwang\log\Log::ERROR );