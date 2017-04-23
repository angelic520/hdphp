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
use Response;

class Route implements Middleware
{
    public function run($next)
    {
        Config::set('controller.app', Config::get('app.path'));
        Config::set('route.cache', Config::get('http.route_cache'));
        Config::set('route.mode', Config::get('http.route_mode'));
        //解析路由
        require ROOT_PATH.'/system/routes.php';
        $this->parse(\Route::dispatch());
        $next();
    }

    /**
     * 处理解析结果
     *
     * @param $result
     */
    protected function parse($result)
    {
        if (IS_AJAX && is_array($result)) {
            Response::ajax($result);
        } else {
            echo is_object($result) ? \View::toString() : $result;
        }
    }
}