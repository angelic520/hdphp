<?php
/*--------------------------------------------------------------------------
| Software: [HDPHP framework]
| Site: www.hdphp.com
|--------------------------------------------------------------------------
| Author: 向军 <2300071698@qq.com>
| WeChat: aihoudun
| Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
|-------------------------------------------------------------------------*/

if ( version_compare( phpversion(), '5.4.0', '<' ) ) {
	die( '<h1 style=\'margin:20px;color:#535353;font:36px/1.5 Helvetica, Arial\'><span style="font-size:150px;">:( </span><br/>Please upgrade to PHP5.4 above</h1>' );
}
require __DIR__ . '/../vendor/autoload.php';
\houdunwang\framework\App::bootstrap();