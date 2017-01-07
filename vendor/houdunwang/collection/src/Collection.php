<?php namespace houdunwang\collection;

use houdunwang\collection\build\Base;

/**
 * 模型多数据集合
 * Class Collection
 * @package hdphp\hd
 */
class Collection {
	protected $link;

	//更改缓存驱动
	protected function driver() {
		$this->link = new Base();

		return $this;
	}

	public function __call( $method, $params ) {
		if ( is_null( $this->link ) ) {
			$this->driver();
		}

		return call_user_func_array( [ $this->link, $method ], $params );
	}

	public static function single() {
		static $link = null;
		if ( is_null( $link ) ) {
			$link = new static();
		}

		return $link;
	}

	public static function __callStatic( $name, $arguments ) {
		return call_user_func_array( [ static::single(), $name ], $arguments );
	}
}