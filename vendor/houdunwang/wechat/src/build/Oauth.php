<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\wechat\build;

use houdunwang\qrcode\QrCode;
use houdunwang\request\Request;

/**
 * 网页授权获取用户基本信息
 * Class oauth
 * @package houdunwang\wechat\build
 */
class oauth extends Base {
	/**
	 * 公共请求方法
	 *
	 * @param string $type 用户资料类型
	 * @param bool $qrCode 使用用二维码获取资料
	 *
	 * @return array|bool|mixed
	 */
	private function request( $type, $qrCode = false ) {
		if ( Request::get( 'code' ) && Request::get( 'state' ) == 'STATE' ) {
			$url  = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->appid . "&secret=" . $this->appsecret . "&code=" . q( 'get.code' ) . "&grant_type=authorization_code";
			$d    = $this->curl( $url );
			$data = $this->get( $d );

			return isset( $data['errcode'] ) ? false : $data;
		} else {
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->appid . "&redirect_uri=" . urlencode( __URL__ ) . "&response_type=code&scope=" . $type . "&state=STATE#wechat_redirect";
			if ( $qrCode ) {
				QrCode::make( $url );
			} else {
				header( 'location:' . $url );
			}
			exit;
		}
	}

	/**
	 * 获取用户openid
	 * @return bool
	 */
	public function snsapiBase() {
		$data = $this->request( 'snsapi_base' );

		return $data ? $data['openid'] : false;
	}

	/**
	 * 是用来获取用户的基本信息的
	 * @return array|bool|mixed
	 */
	public function snsapiUserinfo() {
		$data = $this->request( 'snsapi_userinfo' );
		if ( $data !== false ) {
			$url = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $data['access_token'] . "&openid=" . $data['openid'] . "&lang=zh_CN";
			$res = $this->curl( $url );

			return $this->get( $res );
		}

		return false;
	}

	/**
	 * 扫码登录
	 *
	 * @param \Closure $callback 回调函数
	 */
	public function qrLogin( \Closure $callback ) {
		$data = $this->request( 'snsapi_userinfo', true );
		if ( $data !== false ) {
			$url  = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $data['access_token'] . "&openid=" . $data['openid'] . "&lang=zh_CN";
			$res  = $this->curl( $url );
			$data = $this->get( $res );
		}
		$callback( $data );
	}
}
