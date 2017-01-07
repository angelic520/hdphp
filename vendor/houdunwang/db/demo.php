<?php
require 'vendor/autoload.php';
$config = [
	//缓存表字段
	'cache_field' => true,
	//表字段缓存目录
	'cache_dir'   => 'storage/field',
	//读库列表
	'read'        => [ ],
	//写库列表
	'write'       => [ ],
	//开启读写分离
	'proxy'       => false,
	//主机
	'host'        => 'localhost',
	//类型
	'driver'      => 'mysql',
	//帐号
	'user'        => 'root',
	//密码
	'password'    => 'admin888',
	//数据库
	'database'    => 'demo',
	//表前缀
	'prefix'      => ''
];
\houdunwang\config\Config::set( 'database', $config );

$obj = new \houdunwang\db\Db();
$d   = $obj->query( 'select * from news' );
print_r( $d );
//\houdunwang\db\Db::config( $config );
//print_r( $obj->table( 'news' )->find( 1 ) );