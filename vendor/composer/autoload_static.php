<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit90010f21300c382119f8081579bc3548
{
    public static $files = array (
        'e27462e627a88b1c7e8df9acd31610f4' => __DIR__ . '/..' . '/houdunwang/framework/src/kernel/helper.php',
    );

    public static $prefixLengthsPsr4 = array (
        'w' => 
        array (
            'wechat\\' => 7,
        ),
        's' => 
        array (
            'system\\' => 7,
        ),
        'h' => 
        array (
            'hdphp\\' => 6,
        ),
        'a' => 
        array (
            'app\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'wechat\\' => 
        array (
            0 => __DIR__ . '/..' . '/houdunwang/wechat',
        ),
        'system\\' => 
        array (
            0 => __DIR__ . '/../..' . '/system',
        ),
        'hdphp\\' => 
        array (
            0 => __DIR__ . '/..' . '/houdunwang/framework/src',
        ),
        'app\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit90010f21300c382119f8081579bc3548::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit90010f21300c382119f8081579bc3548::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
