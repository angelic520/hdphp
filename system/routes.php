<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
Route::get( '/c', function () {
	$a->show();
} );

Route::get( '/a', function ( \wechat\src\WeChat $a, $b = 3 ) {
	$a->show();
} );

Route::get( '/user/{id}/{name}', function ($id, $f = '后盾人', \wechat\src\WeChat $a, $name ) {
	echo $id, $name;
	p( $f );
	p( $a );
} )->where( [ 'id' => '[0-9]+', 'name' => '[a-z]+' ] );


