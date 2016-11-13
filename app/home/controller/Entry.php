<?php namespace app\home\controller;

use wechat\src\WeChat;

class Entry {
	public function index( WeChat $a, Entry $b ) {
		return view();
	}

	public function show( WeChat $a ) {
		echo 33;
	}
}