<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

if ( ! function_exists( 'env' ) ) {
	/**
	 * 根据.env配置文件获取匹配项
	 *
	 * @param $name 配置名称
	 * @param $value 为空时的返回值
	 *
	 * @return mixed
	 */
	function env( $name, $value ) {
		$envConfig = [];
		if ( is_file( '.env' ) && empty( $envConfig ) ) {
			$parse = file_get_contents( '.env' );
			$data  = array_filter( preg_split( '@' . PHP_EOL . '@', $parse ) );
			foreach ( $data as $v ) {
				$info                          = explode( '=', $v );
				$envConfig[ trim( $info[0] ) ] = trim( $info[1] );
			}
		}

		return empty( $envConfig[ $name ] ) ? $value : $envConfig[ $name ];
	}
}

if ( ! function_exists( 'pic' ) ) {
	/**
	 * 显示图片
	 * 判断提供的图片文件是否合法
	 * 不是合法图片时返回默认图片替换
	 *
	 * @param $file
	 * @param string $pic
	 *
	 * @return string
	 */
	function pic( $file, $pic = 'resource/images/thumb.jpg' ) {
		if ( preg_match( '@^http@i', $file ) ) {
			return $file;
		} elseif ( empty( $file ) || ! is_file( $file ) ) {
			return __ROOT__ . '/' . $pic;
		} else {
			return __ROOT__ . '/' . $file;
		}
	}
}

if ( ! function_exists( 'u' ) ) {
	/**
	 * 生成url
	 *
	 * @param string $path 模块/动作/方法
	 * @param array $args GET参数
	 *
	 * @return mixed|string
	 */
	function u( $path, $args = [] ) {
		if ( empty( $path ) || preg_match( '@^http@i', $path ) ) {
			return $path;
		}
		$url = C( 'http.rewrite' ) ? __ROOT__ : __ROOT__ . '/' . basename( $_SERVER['SCRIPT_FILENAME'] );
		if ( defined( 'MODULE' ) ) {
			//控制器访问模式
			//URL请求参数
			$urlParam = explode( '/', $_GET[ c( 'http.url_var' ) ] );
			$path     = str_replace( '.', '/', $path );
			switch ( count( explode( '/', $path ) ) ) {
				case 2:
					$path = $urlParam[0] . '/' . $path;
					break;
				case 1:
					$path = $urlParam[0] . '/' . $urlParam[1] . '/' . $path;
					break;
			}

			$url .= '?' . c( 'http.url_var' ) . '=' . $path;
		} else {
			//路由访问模式
			$url .= '?' . c( 'http.url_var' ) . '=' . $path;
		}
		//添加参数
		if ( ! empty( $args ) ) {
			$url .= '&' . http_build_query( $args );
		}

		return $url;
	}
}
if ( ! function_exists( 'url_del' ) ) {
	/**
	 * 从__URL__地址中删除指令的$_GET参数
	 *
	 * @param string|array $args
	 *
	 * @return string
	 */
	function url_del( $args ) {
		if ( ! is_array( $args ) ) {
			$args = [ $args ];
		}
		$url = parse_url( __URL__ );
		parse_str( $url['query'], $output );
		foreach ( $args as $v ) {
			if ( isset( $output[ $v ] ) ) {
				unset( $output[ $v ] );
			}
		}
		$url = $url['scheme'] . '://' . $url['host'] . $url['path'] . '?';
		foreach ( $output as $k => $v ) {
			$url .= $k . '=' . $v . '&';
		}

		return trim( $url, '&' );
	}
}

/**
 * 输出404页面
 */
if ( ! function_exists( '_404' ) ) {
	function _404() {
		\Response::sendHttpStatus( 302 );
		if ( is_file( c( 'app.404' ) ) ) {
			die( view( c( 'app.404' ) ) );
		}
		exit;
	}
}

if ( ! function_exists( 'p' ) ) {
	/**
	 * 打印输出数据
	 *
	 * @param $var
	 */
	function p( $var ) {
		echo "<pre>" . print_r( $var, true ) . "</pre>";
	}
}

if ( ! function_exists( 'dd' ) ) {
	/**
	 * 打印数据有数据类型
	 *
	 * @param $var
	 */
	function dd( $var ) {
		ob_start();
		var_dump( $var );
		echo "<pre>" . ob_get_clean() . "</pre>";
		exit;
	}
}

if ( ! function_exists( 'go' ) ) {
	/**
	 * 跳转网址
	 *
	 * @param string $url url地址
	 * @param int $time 等待时间
	 * @param string $msg 提示信息
	 */
	function go( $url, $time = 0, $msg = '' ) {
		$url = u( $url );
		if ( ! headers_sent() ) {
			$time == 0 ? header( "Location:" . $url ) : header( "refresh:{$time};url={$url}" );
			exit( $msg );
		} else {
			echo "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
			if ( $msg ) {
				echo( $msg );
			}
			exit;
		}
	}
}

if ( ! function_exists( 'print_const' ) ) {
	/**
	 * 打印用户常量
	 */
	function print_const() {
		$d = get_defined_constants( true );
		p( $d['user'] );
	}
}

if ( ! function_exists( 'v' ) ) {
	/**
	 * 全局变量
	 *
	 * @param null $name 变量名
	 * @param string $value 变量值
	 *
	 * @return array|mixed|null|string
	 */
	function v( $name = null, $value = '[null]' ) {
		static $vars = [];
		if ( is_null( $name ) ) {
			return $vars;
		} else if ( $value == '[null]' ) {
			//取变量
			$tmp = $vars;
			foreach ( explode( '.', $name ) as $d ) {
				if ( isset( $tmp[ $d ] ) ) {
					$tmp = $tmp[ $d ];
				} else {
					return null;
				}
			}

			return $tmp;
		} else {
			//设置
			$tmp = &$vars;
			foreach ( explode( '.', $name ) as $d ) {
				if ( ! isset( $tmp[ $d ] ) ) {
					$tmp[ $d ] = [];
				}
				$tmp = &$tmp[ $d ];
			}

			return $tmp = $value;
		}
	}
}

if ( ! function_exists( 'confirm' ) ) {
	/**
	 * 有确定提示的提示页面
	 *
	 * @param string $message 提示文字
	 * @param string $sUrl 确定按钮跳转的url
	 * @param string $eUrl 取消按钮跳转的url
	 */
	function confirm( $message, $sUrl, $eUrl ) {
		View::with( [ 'message' => $message, 'sUrl' => $sUrl, 'eUrl' => $eUrl ] );
		echo view( Config::get( 'app.confirm' ) );
		exit;
	}
}

if ( ! function_exists( 'message' ) ) {
	/**
	 * 消息提示
	 *
	 * @param string $content 消息内容
	 * @param string $redirect 跳转地址有三种方式 1:back(返回上一页)  2:refresh(刷新当前页)  3:具体Url
	 * @param string $type 信息类型  success(成功），error(失败），warning(警告），info(提示）
	 * @param int $timeout 等待时间
	 */
	function message( $content, $redirect = 'back', $type = 'success', $timeout = 2 ) {
		if ( IS_AJAX ) {
			ajax( [ 'valid' => $type == 'success' ? 1 : 0, 'message' => $content ] );
		} else {
			switch ( $redirect ) {
				case 'with':
					\Session::set( 'errors', is_array( $content ) ? $content : [ $content ] );
					echo '<script>location.href="' . $_SERVER['HTTP_REFERER'] . '";</script>';
					exit;
					break;
				case 'back':
					//有回调地址时回调,没有时返回主页
					$url = "window.history.go(-1)";
					break;
				case 'refresh':
					$url = "location.replace('" . __URL__ . "')";
					break;
				default:
					if ( empty( $redirect ) ) {
						$url = 'window.history.go(-1)';
					} else {
						$url = "location.replace('" . u( $redirect ) . "')";
					}
					break;
			}
			//图标
			switch ( $type ) {
				case 'success':
					$ico = 'fa-check-circle';
					break;
				case 'error':
					$ico = 'fa-times-circle';
					break;
				case 'info':
					$ico = 'fa-info-circle';
					break;
				case 'warning':
					$ico = 'fa-warning';
					break;
			}
			View::with( [
				'content'  => $content,
				'redirect' => $redirect,
				'type'     => $type,
				'url'      => $url,
				'ico'      => $ico,
				'timeout'  => $timeout * 1000
			] );
			echo view( Config::get( 'app.message' ) );
		}
		exit;
	}
}

if ( ! function_exists( 'csrf_field' ) ) {
	/**
	 * CSRF 表单
	 * @return string
	 */
	function csrf_field() {
		return "<input type='hidden' name='csrf_token' value='" . Session::get( 'csrf_token' ) . "'/>\r\n";
	}
}

if ( ! function_exists( 'method_field' ) ) {
	/**
	 * CSRF 表单
	 *
	 * @param $type
	 *
	 * @return string
	 */
	function method_field( $type ) {
		return "<input type='hidden' name='_method' value='" . strtoupper( $type ) . "'/>\r\n";
	}
}
if ( ! function_exists( 'csrf_token' ) ) {
	/**
	 * CSRF 值
	 * @return mixed
	 */
	function csrf_token() {
		return Session::get( 'csrf_token' );
	}
}
