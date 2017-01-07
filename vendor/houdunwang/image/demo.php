<?php
require 'vendor/autoload.php';
$config = [
	//水印字体
	'font'       => '',
	//水印图像
	'image'      => '',
	//位置  1~9九个位置  0为随机
	'pos'        => 9,
	//透明度
	'pct'        => 60,
	//压缩比
	'quality'    => 80,
	//水印文字
	'text'       => 'houdunwang.com',
	//文字颜色
	'text_color' => '#f00f00',
	//文字大小
	'text_size'  => 12,
];
\houdunwang\config\Config::set( 'image', $config );