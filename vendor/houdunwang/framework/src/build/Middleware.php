<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace houdunwang\framework\build;

use houdunwang\framework\middleware\App;
use houdunwang\framework\middleware\Cli;
use houdunwang\framework\middleware\Cookie;
use houdunwang\framework\middleware\Csrf;
use houdunwang\framework\middleware\Request;
use houdunwang\framework\middleware\Route;
use houdunwang\framework\middleware\Session;
use houdunwang\framework\middleware\View;

/**
 * Class Middleware
 *
 * @package houdunwang\framework\build
 */
trait Middleware
{
    //中间件
    protected $middlewarte
        = [
            App::class,
            Cli::class,
            Cookie::class,
            Session::class,
            Request::class,
            Csrf::class,
            View::class,
            Route::class,
        ];

    /**
     * 执行中间件
     *
     * @param array $middleware
     */
    protected function middleware(array $middleware = [])
    {
        $middleware = array_reverse($middleware ?: $this->middlewarte);
        $dispatcher = array_reduce(
            $middleware,
            $this->getSlice(),
            function () {
            }
        );
        $dispatcher();
    }

    protected function getSlice()
    {
        return function ($next, $step) {
            return function () use ($next, $step) {
                return call_user_func_array([new $step, 'run'], [$next]);
            };
        };
    }
}