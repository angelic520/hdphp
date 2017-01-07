<?php namespace houdunwang\framework\middleware;

use houdunwang\config\Config;

/**
 * 表单令牌验证
 * Class Csrf
 * @package hdphp\middleware
 */
class Csrf {
	public function run() {
		if ( Config::get( 'csrf.open' ) ) {
			/**
			 * 获取令牌值用于比较
			 * 令牌不存在时生成新的令牌
			 */
			$token = Session::get( 'csrf_token' );
			if ( empty( $token ) ) {
				Session::set( 'csrf_token', md5( clientIp() . microtime( true ) ) );
			}
			/**
			 * 当为POST请求时并且为同域名时验证令牌
			 */
			if ( Request::post() && Request::isDomain() ) {
				if ( Request::post( 'csrf_token' ) != $token ) {
					//存在过滤的验证时忽略验证
					$except = Config::get( 'csrf.except' );
					foreach ( (array) $except as $f ) {
						if ( preg_match( "@$f@i", __URL__ ) ) {
							return;
						}
					}
					throw new \Exception( 'CSRF 令牌验证失败' );
				}
			}
		}

	}
}