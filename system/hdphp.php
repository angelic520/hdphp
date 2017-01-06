<?php
// .-------------------------------------------------------------------
// |  Software: [HDPHP framework]
// |      Site: www.hdphp.com
// |-------------------------------------------------------------------
// |    Author: 向军 <2300071698@qq.com>
// |    WeChat: houdunwangxj
// | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
// '-------------------------------------------------------------------
header( "Content-type:text/html;charset=utf-8" );
if ( version_compare( phpversion(), '5.4.0', '<' ) ) {
	die( '<h1 style=\'margin:20px;color:#535353;font:36px/1.5 "PingFang SC", Helvetica, "Helvetica Neue", "微软雅黑", Tahoma, Arial, sans-serif\'><span style="font-size:150px;">:( </span><br/>PHP要求版本>=5.4</h1>' );
}
//composer自动加载
require __DIR__ . '/../vendor/autoload.php';
define( 'ROOT_PATH', dirname( __DIR__ ) );
define( 'HDPHP_VERSION', '3.0.12' );
//启动应用
\houdunwang\framework\App::bootstrap();