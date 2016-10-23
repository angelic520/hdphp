<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2a11f840fc19d051d1322113da9229dc
{
    public static $files = array (
        'e27462e627a88b1c7e8df9acd31610f4' => __DIR__ . '/..' . '/houdunwang/framework/src/kernel/helper.php',
    );

    public static $prefixLengthsPsr4 = array (
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit2a11f840fc19d051d1322113da9229dc::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2a11f840fc19d051d1322113da9229dc::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
