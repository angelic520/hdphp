<?php namespace houdunwang\framework\middleware;

use houdunwang\config\Config;
use houdunwang\request\Request;

/**
 * 表单令牌验证
 * Class Csrf
 * @package hdphp\middleware
 */
class Csrf {
	public function run() {
//		p($_GET);
//		p($_POST);
//		VAR_DUMP(IS_POST);return;
		//当为POST请求时并且为同域名时验证令牌
		if ( Request::isDomain() && Config::get( 'csrf.open' ) && Request::post() ) {
			//比较POST中提交的CSRF
			if ( Request::post( 'csrf_token' ) == $this->get() ) {
				return true;
			}
			//根据头部数据验证CSRF
			$headers = getallheaders();
			if ( isset( $headers['X-CSRF-TOKEN'] ) && $headers['X-CSRF-TOKEN'] == $this->get() ) {
				return true;
			}
			//存在过滤的验证时忽略验证
			$except = Config::get( 'csrf.except' );
			foreach ( (array) $except as $f ) {
				if ( preg_match( "@$f@i", __URL__ ) ) {
					return true;
				}
			}
			throw new \Exception( 'CSRF 令牌验证失败' );
		}
	}

	/**
	 * 获取令牌
	 * 如果不存是创建新令牌
	 * @return string
	 */
	protected function get() {
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

		return $token;
	}
}