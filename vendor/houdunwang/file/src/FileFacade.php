<?php namespace houdunwang\file;
use houdunwang\framework\build\Facade;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

class FileFacade extends Facade {
	public static function getFacadeAccessor() {
		return 'File';
	}
}