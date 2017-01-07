<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\lang\build;

use houdunwang\arr\Arr;

class Base {
	//语句包
	private $language;
	//配置
	protected $config;

	public function __construct() {
	}

	//设置配置项
	public function config( $config, $value = null ) {
		if ( is_array( $config ) ) {
			$this->config = $config;
		} else if ( is_null( $value ) ) {
			return Arr::get( $this->config, $config );
		} else {
			$this->config = Arr::set( $this->config, $config, $value );
		}
		$this->file( $this->config( 'file' ) );

		return $this;
	}

	/**
	 * 语言包文件
	 * @param $file
	 */
	public function file( $file ) {
		$this->language = include $file;
	}

	//获取语言
	public function get( $lang ) {
		return Arr::get( $this->language, $lang );
	}

	//设置单个语言
	public function set( $name, $value ) {
		return Arr::set( $this->language, $name, $value );
	}
}