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
use Config;
use houdunwang\view\build\Base as ViewBase;
use Response;

class Route implements Middleware
{
    public function run($next)
    {
        if (RUN_MODE == 'HTTP') {
            Config::set('controller.app', Config::get('app.path'));
            Config::set('route.cache', Config::get('http.route_cache'));
            //解析路由
            require ROOT_PATH.'/system/routes.php';
            \Route::dispatch();
            $this->parse();
        }
        $next();
    }

    /**
     * 处理解析结果
     *
     */
    protected function parse()
    {
        if (RUN_MODE == 'HTTP') {
            $result = \Route::getResult();
            if (is_array($result)) {
                echo json_encode($result, JSON_UNESCAPED_UNICODE);
            }
            if (is_string($result)) {
                echo $result;
            }
            if ($result instanceof ViewBase) {
                echo \View::toString();
            }
        }
    }
}