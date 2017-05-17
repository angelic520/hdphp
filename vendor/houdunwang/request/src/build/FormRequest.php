<?php
/** .-------------------------------------------------------------------
 * |  Software: [HDPHP framework]
 * |      Site: www.hdphp.com  www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace houdunwang\request\build;

/**
 * 表单请求基础类
 * Class Request
 *
 * @package houdunwang\request\build
 */
abstract class FormRequest implements \ArrayAccess
{
    //验证失败时的跳转页面
    protected $home = '/';

    /**
     * @return mixed
     */
    abstract public function authorize();

    /**
     * @return mixed
     */
    abstract public function rules();

    protected $data = [];

    public function __construct()
    {
        if ($this->authorize() !== true) {
            go(__WEB__.$this->home);
        }
        $this->data = \Request::post();
        $this->validate();
    }

    final private function validate()
    {
        if ( ! empty($this->data)) {
            Validate::make($this->rules(), $this->data);
        }
    }

    public function offsetSet($key, $value)
    {
    }

    public function offsetGet($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    public function offsetExists($key)
    {
        return isset($this->data[$key]);
    }

    public function offsetUnset($key)
    {
    }

    public function __get($name)
    {
        return isset($this[$name]) ? $this[$name] : null;
    }

    public function all()
    {
        return $this->data;
    }

    public function __invoke()
    {
        return $this->all();
    }
}