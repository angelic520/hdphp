<?php
//微信配置
return [
	/*
	|--------------------------------------------------------------------------
	| token
	|--------------------------------------------------------------------------
	| 微信绑定验证时使用的token, 需要与微信公众号后台保持一致
	| http://mp.weixin.qq.com/wiki/8/f9a0b8382e0b77d87b3bcc1ce6fbc104.html
	*/
	'token'          => '',

	/*
	|--------------------------------------------------------------------------
	| encodingaeskey
	|--------------------------------------------------------------------------
	| 需要与微信公众号后台保持一致
	| http://mp.weixin.qq.com/wiki/8/f9a0b8382e0b77d87b3bcc1ce6fbc104.html
	*/
	'encodingaeskey' => '',

	/*
	|--------------------------------------------------------------------------
	| 公众号身份标识
	|--------------------------------------------------------------------------
	| 填写公众号认证后腾讯官网发来的邮件中的appid
	*/
	'appid'          => '',

	/*
	|--------------------------------------------------------------------------
	| appsecret
	|--------------------------------------------------------------------------
	| 公众平台API的权限获取所需密钥Key
	| 需要与微信公众号后台保持一致
	*/
	'appsecret'      => '',

	/*
	|--------------------------------------------------------------------------
	| 支付商户号
	|--------------------------------------------------------------------------
	| 微信发来的邮件中的微信支付商户号
	*/
	'mch_id'         => '',

	/*
	|--------------------------------------------------------------------------
	| 商户支付密钥
	|--------------------------------------------------------------------------
	| 需要与腾讯商户后台API密钥保持一致
	*/
	'key'            => '',

	/*
	|--------------------------------------------------------------------------
	| 异步通知回调地址
	|--------------------------------------------------------------------------
	| 接收微信支付异步通知回调地址
	| 不能携带参数,需要先声明路由
	*/
	'notify_url'     => __ROOT__ . '/index.php/wxnotifyurl',

	/*
	|--------------------------------------------------------------------------
	| 回调地址
	|--------------------------------------------------------------------------
	| 支付成功同步回调地址
	*/
	'back_url'       => 'http://www.houdunwang.com',

	/*
	|--------------------------------------------------------------------------
	| 证书
	|--------------------------------------------------------------------------
	| 使用微信红包接口等功能时需要的证书,请登录微信支付后台下载
	*/
	'apiclient_cert' => 'cert/apiclient_cert.pem',
	'apiclient_key'  => 'cert/apiclient_key.pem',
	'rootca'         => 'cert/rootca.pem',
];