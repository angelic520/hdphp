#错误调试

开发过程中使用调试模式可以将所遇到的所有问题呈现给开发者，加快解决错误的速度。
框架错误处理非常高效全面，只需要开启调试模式剩下的就交给框架自动完成。 

组件依赖 [Log日志组件](https://github.com/houdunwang/log) 请参考 [GitHub文档](https://github.com/houdunwang/log) 先行配置Log组件。
[TOC]

####安装
使用 composer 命令进行安装或下载源代码使用。

```
composer require houdunwang/error
```
> HDPHP 框架已经内置此组件，无需要安装

####配置
```
$config = [
	//开启时直接显示错误信息
	'debug'       => true,
	//Notice类型错误显示
	'show_notice' => true,
	//错误提示页面
	'bug'         => 'resource/bug.php',
];
\houdunwang\config\Config::set( 'error', $config );
```

####启动
```
\houdunwang\error\Error::bootstrap();
```