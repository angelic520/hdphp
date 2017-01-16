<?php namespace houdunwang\weixin;


use houdunwang\framework\build\Facade;

class WeChatFacade extends Facade {
	public static function getFacadeAccessor() {
		return 'WeChat';
	}
}