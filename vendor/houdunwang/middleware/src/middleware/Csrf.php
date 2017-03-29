<?php namespace houdunwang\middleware\middleware;

use houdunwang\config\Config;
use houdunwang\request\Request;
use houdunwang\session\Session;

/**
 * 表单令牌验证
 * Class Csrf
 * @package houdunwang\middleware\middleware
 */
class Csrf {
	//验证令牌
	protected $token;

	public function run() {
		//设置令牌
		$this->setToken();
		//当为POST请求时并且为同域名时验证令牌
		if ( Request::isDomain() && Config::get( 'csrf.open' ) && Request::post() ) {
			//比较POST中提交的CSRF
			if ( Request::post( 'csrf_token' ) == $this->token ) {
				return true;
			}
			//根据头部数据验证CSRF
			$headers = \Arr::keyCase( getallheaders(), 1 );
			if ( isset( $headers['X-CSRF-TOKEN'] ) && ( $headers['X-CSRF-TOKEN'] == $this->token ) ) {
				return true;
			}
			//存在过滤的验证时忽略验证
			$except = Config::get( 'csrf.except' );
			foreach ( (array) $except as $f ) {
				if ( preg_match( "@$f@i", __URL__ ) ) {
					return true;
				}
			}
			message( 'CSRF表单令牌验证失败', '', 'error' );
		}
	}

	/**
	 * 设置令牌
	 * 如果不存是创建新令牌
	 */
	protected function setToken() {
		if ( Config::get( 'csrf.open' ) ) {
			$token = Session::get( 'csrf_token' );
			if ( empty( $token ) ) {
				$token = md5( clientIp() . microtime( true ) );
				Session::set( 'csrf_token', $token );
				/**
				 * 生成COOKIE令牌
				 * 一些框架如AngularJs等框架会自动根据COOKIE中的token提交令牌
				 */
				Cookie::set( 'XSRF-TOKEN', $token );
			}
			$this->token = $token;
		}
	}
}