# 目录操作

目录组件用于对常用目录操作的实现

[TOC]
##开始使用

####安装组件
使用 composer 命令进行安装或下载源代码使用。

```
composer require houdunwang/dir
```
> HDPHP 框架已经内置此组件，无需要安装

####创建目录
```
\houdunwang\dir\Dir::create('Home/View');
```

####删除目录
```
\houdunwang\dir\Dir::del('Home');
```

####复制目录
```
\houdunwang\dir\Dir::copy('a','b');
```

####删除文件
```
\houdunwang\dir\Dir::delFile($file);
```

####移动目录
```
\houdunwang\dir\Dir::move('a','b');
```

####目录树
```
\houdunwang\dir\Dir::tree('Home');
```

####目录大小
```
\houdunwang\dir\Dir::size('Home');
```

####移动文件
```
\houdunwang\dir\Dir::moveFile('hd.php','data');
//将hd.php 移动到data目录
```