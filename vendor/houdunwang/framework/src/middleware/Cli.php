<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace houdunwang\framework\middleware;


use houdunwang\middleware\build\Middleware;

class Cli implements Middleware
{
    public function run($next)
    {
        //执行命令行指令
        \houdunwang\cli\Cli::bootstrap();
        $next();
    }
}