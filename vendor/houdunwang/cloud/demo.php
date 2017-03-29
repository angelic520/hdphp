<?php
require 'vendor/autoload.php';
$config = [
	//登录后盾云获取授权信息
	'uid'    => 'file',
	//通信密钥
	'secret' => '',
];
$data = ['code'=>'短信模板CODE','mobile'=>'手机号'];
\houdunwang\cloud\Cloud::sms($data);
