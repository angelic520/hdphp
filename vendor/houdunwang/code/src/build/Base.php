<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\code\build;

use houdunwang\session\Session;

class Base {
	protected $config = [
		//画布宽度
		'width'     => 100,
		//画布高度
		'$height'   => 30,
		//背景颜色
		'bgColor'   => '#ffffff',
		//验证码的随机种子
		'codeStr'   => '23456789abcdefghjkmnpqrstuvwsyz',
		//验证码长度
		'codeLen'   => 4,
		//验证码字体
		'font'      => '',
		//验证码字体大小
		'fontSize'  => 16,
		//验证码字体颜色
		'fontColor' => ''
	];
	//资源
	private $img;
	//验证码
	private $code;
	//随机
//	//画布宽度
//	private $width = 100;
//	//画布高度
//	private $height = 30;
//	//背景颜色
//	private $bgColor = '#ffffff';
//	//验证码的随机种子
//	private $codeStr = '23456789abcdefghjkmnpqrstuvwsyz';
//	//验证码长度
//	private $codeLen = 4;
//	//验证码字体
//	private $font = '';
//	//验证码字体大小
//	private $fontSize = 16;
//	//验证码字体颜色
//	private $fontColor = '';

	/**
	 * 配置管理
	 *
	 * @param $config
	 * @param null $value
	 *
	 * @return $this
	 */
	public function config( $config, $value = null ) {
		if ( is_array( $config ) ) {
			$this->config = $config;
		} else if ( is_null( $value ) ) {
			return Arr::get( $this->config, $config );
		} else {
			$this->config = Arr::set( $this->config, $config, $value );
		}

		return $this;
	}

	//创建验证码
	public function make() {
		$this->create();//生成验证码
		header( "Content-type:image/png" );
		imagepng( $this->img );
		imagedestroy( $this->img );
		exit;
	}

	//设置字体文件
	public function font( $font ) {
		$this->config( 'font', $font );

		return $this;
	}

	//设置文字大小
	public function fontSize( $fontSize ) {
		$this->config( 'fontSize', $fontSize );

		return $this;
	}

	//设置字体颜色
	public function fontColor( $fontColor ) {
		$this->config( 'fontColor', $fontColor );

		return $this;
	}

	//验证码数量
	public function num( $num ) {
		$this->config( 'codeLen', $num );

		return $this;
	}

	//设置宽度
	public function width( $width ) {
		$this->config( 'width', $width );

		return $this;
	}

	//设置高度
	public function height( $height ) {
		$this->config( 'height', $height );

		return $this;
	}

	//设置背景颜色
	public function background( $color ) {
		$this->config( 'bgColor', $color );

		return $this;
	}

	/**
	 * 验证验证码
	 *
	 * @param string $field 表单字段
	 *
	 * @return bool
	 */
	public function auth( $field = 'code' ) {
		return ! isset( $_POST[ $field ] ) || strtoupper( $_POST[ $field ] ) == Code::get();
	}

	//返回验证码
	public function get() {
		return Session::get( 'code' );
	}

	//生成验证码
	private function createCode() {
		$code = '';
		for ( $i = 0; $i < $this->config['codeLen']; $i ++ ) {
			$code .= $this->config['codeStr'] [ mt_rand( 0, strlen( $this->config['codeStr'] ) - 1 ) ];
		}
		$this->code = strtoupper( $code );

		return Session::set( 'code', $this->code );
	}

	//建画布
	private function create() {
		if ( ! $this->checkGD() ) {
			return false;
		}
		$w       = $this->config['width'];
		$h       = $this->config['height'];
		$bgColor = $this->config['bgColor'];
		$img     = imagecreatetruecolor( $w, $h );
		$bgColor = imagecolorallocate( $img, hexdec( substr( $bgColor, 1, 2 ) ), hexdec( substr( $bgColor, 3, 2 ) ), hexdec( substr( $bgColor, 5, 2 ) ) );
		imagefill( $img, 0, 0, $bgColor );
		$this->img = $img;
		$this->createLine();
		$this->createFont();
		$this->createPix();
		$this->createRec();
	}

	//画线
	private function createLine() {
		$w          = $this->config['width'];
		$h          = $this->config['height'];
		$line_color = "#dcdcdc";
		$color      = imagecolorallocate( $this->img, hexdec( substr( $line_color, 1, 2 ) ), hexdec( substr( $line_color, 3, 2 ) ), hexdec( substr( $line_color, 5, 2 ) ) );
		$l          = $h / 5;
		for ( $i = 1; $i < $l; $i ++ ) {
			$step = $i * 5;
			imageline( $this->img, 0, $step, $w, $step, $color );
		}
		$l = $w / 10;
		for ( $i = 1; $i < $l; $i ++ ) {
			$step = $i * 10;
			imageline( $this->img, $step, 0, $step, $h, $color );
		}
	}

	//画矩形边框
	private function createRec() {
		//imagerectangle($this->img, 0, 0, $this->config['width'] - 1, $this->config['height'] - 1, $this->config['fontColor']);
	}

	//写入验证码文字
	private function createFont() {
		$this->createCode();
		$color = $this->config['fontColor'];
		if ( ! empty( $color ) ) {
			$fontColor = imagecolorallocate( $this->img, hexdec( substr( $color, 1, 2 ) ), hexdec( substr( $color, 3, 2 ) ), hexdec( substr( $color, 5, 2 ) ) );
		}
		$x = ( $this->config['width'] - 10 ) / $this->config['codeLen'];
		for ( $i = 0; $i < $this->config['codeLen']; $i ++ ) {
			if ( empty( $color ) ) {
				$fontColor = imagecolorallocate( $this->img, mt_rand( 50, 155 ), mt_rand( 50, 155 ), mt_rand( 50, 155 ) );
			}
			imagettftext( $this->img, $this->config['fontSize'], mt_rand( - 30, 30 ), $x * $i + mt_rand( 6, 10 ), mt_rand( $this->config['height'] / 1.3, $this->config['height'] - 5 ), $fontColor, $this->config['font'], $this->code [ $i ] );
		}
		$this->config['fontColor'] = $fontColor;
	}

	//画线
	private function createPix() {
		$pix_color = $this->config['fontColor'];
		for ( $i = 0; $i < 50; $i ++ ) {
			imagesetpixel( $this->img, mt_rand( 0, $this->config['width'] ), mt_rand( 0, $this->config['height'] ), $pix_color );
		}
		for ( $i = 0; $i < 2; $i ++ ) {
			imageline( $this->img, mt_rand( 0, $this->config['width'] ), mt_rand( 0, $this->config['height'] ), mt_rand( 0, $this->config['width'] ), mt_rand( 0, $this->config['height'] ), $pix_color );
		}
		//画圆弧
		for ( $i = 0; $i < 1; $i ++ ) {
			// 设置画线宽度
			imagearc( $this->img, mt_rand( 0, $this->config['width'] ), mt_rand( 0, $this->config['height'] ), mt_rand( 0, $this->config['width'] ), mt_rand( 0, $this->config['height'] ), mt_rand( 0, 160 ), mt_rand( 0, 200 ), $pix_color );
		}
		imagesetthickness( $this->img, 1 );
	}

	//验证GD库
	private function checkGD() {
		return extension_loaded( 'gd' ) && function_exists( "imagepng" );
	}

}