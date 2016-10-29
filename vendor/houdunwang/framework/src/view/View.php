<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
namespace hdphp\view;

use Exception;

//视图处理
class View {
	//模板变量集合
	protected static $vars = [ ];
	//模版文件
	protected $tpl;
	//编译文件
	protected $compileFile;
	//缓存目录
	protected $cacheDir;
	//缓存时间
	protected $expire;

	/**
	 * 初始化模板数据
	 *
	 * @param string $file 文件名
	 */
	public function init( $file ) {
		if ( empty( $this->tpl ) ) {
			$this->cacheDir    = ROOT_PATH . '/storage/view/cache';
			$this->tpl         = $this->getTemplateFile( $file );
			$this->compileFile = ROOT_PATH . '/storage/view/' . preg_replace( '/[^\w]/', '_', $this->tpl ) . '_' . substr( md5( $this->tpl ), 0, 5 ) . '.php';
		}
	}

	/**
	 * 解析模板
	 *
	 * @param string $tpl
	 * @param int $expire
	 *
	 * @return $this
	 */
	public function make( $tpl = '', $expire = 0 ) {
		$this->init( $tpl );
		$this->expire = $expire;
		//缓存有效
		if ( ! c( 'app.debug' ) && $expire > 0 && $this->isCache() ) {
			return $this;
		}
		//编译文件
		$this->compile();

		return $this;
	}

	//返回模板解析后的字符
	public function fetch( $tpl = '', $expire = 0 ) {
		return $this->make( $tpl, $expire )->__toString();
	}

	//缓存标识
	protected function getCacheName() {
		return md5( $_SERVER['REQUEST_URI'] . $this->tpl );
	}

	//获取编译文件
	public function getCompileFile() {
		return $this->compileFile;
	}

	//获取当前实例模板文件
	public function getTpl() {
		return $this->tpl;
	}

	//根据文件名获取模板文件
	protected function getTemplateFile( $file ) {
		if ( empty( $this->tpl ) ) {
			//没有扩展名时添加上
			if ( $file && ! preg_match( '/\.[a-z]+$/i', $file ) ) {
				$file .= c( 'view.prefix' );
			}
			if ( ! is_file( $file ) ) {
				if ( defined( 'MODULE' ) ) {
					//模块视图文件夹
					$file = strtolower( MODULE_PATH . '/view/' . CONTROLLER ) . '/' . ( $file ?: ACTION . c( 'view.prefix' ) );
					if ( ! is_file( $file ) ) {
						throw new Exception( "模板不存在:$file" );
					}
				} else {
					//路由访问时
					$file = ROOT_PATH . '/' . c( 'view.path' ) . '/' . $file . c( 'view.prefix' );
					if ( ! is_file( $file ) ) {
						throw new Exception( "模板不存在:$file" );
					}
				}
			}
		}

		return $this->tpl = $file;
	}

	//验证缓存文件
	public function isCache( $tpl = '' ) {
		static $status = null;
		$this->init( $tpl );
		if ( is_null( $status ) ) {
			$status = Cache::dir( $this->cacheDir )->get( $this->getCacheName() ) ? true : false;
		}

		return $status;
	}

	//删除模板缓存
	public function delCache() {
		return Cache::dir( $this->cacheDir )->del( $this->getCacheName() );
	}

	//编译文件
	protected function compile() {
		$status = c( 'app.debug' )
		          || ! is_file( $this->compileFile )
		          || ( filemtime( $this->tpl ) > filemtime( $this->compileFile ) );
		if ( $status ) {
			Dir::create( dirname( $this->compileFile ) );
			//执行文件编译
			$compile = new Compile( $this );
			$content = $compile->run();
			file_put_contents( $this->compileFile, $content );
		}
	}

	/**
	 * 分配变量
	 *
	 * @param mixed $name 变量名
	 * @param string $value 值
	 *
	 * @return $this
	 */
	public function with( $name, $value = '' ) {
		if ( is_array( $name ) ) {
			foreach ( $name as $k => $v ) {
				self::$vars[ $k ] = $v;
			}
		} else {
			self::$vars[ $name ] = $value;
		}

		return $this;
	}

	//显示模板
	public function __toString() {
		if ( $this->isCache() ) {
			//缓存有效时返回缓存数据
			return Cache::dir( $this->cacheDir )->get( $this->getCacheName() ) ?: '';
		} else {
			ob_start();
			extract( self::$vars );
			include $this->compileFile;
			$content = ob_get_clean();
			//创建缓存文件
			if ( ! c( 'app.debug' ) && $this->expire > 0 ) {
				Cache::dir( $this->cacheDir )->set( $this->getCacheName(), $content, $this->expire );
			}

			return $content;
		}
	}
}