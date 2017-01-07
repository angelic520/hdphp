<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace houdunwang\middleware\build;

use houdunwang\config\Config;
use houdunwang\container\Container;

class Base {
	protected $run = [ ];
	protected $config;

	//设置配置项
	public function config( $config = null, $value = null ) {
		if ( is_null( $config ) ) {
			return $this->config;
		} else if ( is_array( $config ) ) {
			$this->config = $config;

			return $this;
		} else if ( is_null( $value ) ) {
			return Arr::get( $this->config, $config );
		} else {
			$this->config = Arr::set( $this->config, $config, $value );

			return $this;
		}
	}

	/**
	 * 添加控制器执行的中间件
	 *
	 * @param string $name 中间件名称
	 * @param array $mod 类型
	 *  ['only'=>array('a','b')] 仅执行a,b控制器动作
	 *  ['except']=>array('a','b')], 除了a,b控制器动作
	 */
	public function set( $name, $mod = [ ] ) {
		if ( $mod ) {
			foreach ( $mod as $type => $data ) {
				switch ( $type ) {
					case 'only':
						if ( in_array( ACTION, $data ) ) {
							$this->run[] = $this->config( 'controller.' . $name );
						}
						break;
					case 'except':
						if ( ! in_array( ACTION, $data ) ) {
							$this->run[] = $this->config( 'controller.' . $name );
						}
						break;
				}
			}
		} else {
			$this->run[] = $this->config( 'controller.' . $name );
		}
	}

	//执行控制器中间件
	public function controller() {
		foreach ( $this->run as $class ) {
			if ( class_exists( $class ) ) {
				Container::callMethod( $class, 'run' );
			}
		}
	}

	//执行全局中间件
	public function globals() {
		$middleware = array_unique( $this->config( 'global' ) );
		foreach ( $middleware as $class ) {
			if ( class_exists( $class ) ) {
				Container::callMethod( $class, 'run' );
			}
		}
	}

	/**
	 * 执行应用中间件
	 *
	 * @param $name
	 *
	 * @return mixed
	 */
	public function exe( $name ) {
		$class = $this->config( 'web.' . $name );
		if ( is_array( $class ) ) {
			//数组配置时
			foreach ( $class as $c ) {
				if ( class_exists( $c ) ) {
					return Container::callMethod( $c, 'run' );
				}
			}
		} else {
			if ( class_exists( $class ) ) {
				return Container::callMethod( $class, 'run' );
			}
		}
	}
}