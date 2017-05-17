<?php namespace houdunwang\model;

/** .-------------------------------------------------------------------
 * |  Software: [HDCMS framework]
 * |      Site: www.hdcms.com
 * |-------------------------------------------------------------------
 * |    Author: 向军 <2300071698@qq.com>
 * |    WeChat: aihoudun
 * | Copyright (c) 2012-2019, www.houdunwang.com. All Rights Reserved.
 * '-------------------------------------------------------------------*/
use ArrayAccess;
use houdunwang\arr\Arr;
use houdunwang\collection\Collection;
use houdunwang\db\Db;
use houdunwang\db\Query;
use houdunwang\model\build\ArrayIterator;
use houdunwang\model\build\Auto;
use houdunwang\model\build\Filter;
use houdunwang\model\build\Relation;
use houdunwang\model\build\Validate;
use Iterator;
use PHPUnit\Runner\Exception;

class Model implements ArrayAccess, Iterator
{
    use ArrayIterator, Relation, Validate, Auto, Filter;

    //----------自动验证----------
    //有字段时验证
    const EXIST_VALIDATE = 1;
    //值不为空时验证
    const NOT_EMPTY_VALIDATE = 2;
    //必须验证
    const MUST_VALIDATE = 3;
    //值是空时验证
    const EMPTY_VALIDATE = 4;
    //不存在字段时处理
    const NOT_EXIST_VALIDATE = 5;
    //----------自动完成----------
    //有字段时验证
    const EXIST_AUTO = 1;
    //值不为空时验证
    const NOT_EMPTY_AUTO = 2;
    //必须验证
    const MUST_AUTO = 3;
    //值是空时验证
    const EMPTY_AUTO = 4;
    //不存在字段时处理
    const NOT_EXIST_AUTO = 5;
    //----------自动过滤----------
    //有字段时验证
    const EXIST_FILTER = 1;
    //值不为空时验证
    const NOT_EMPTY_FILTER = 2;
    //必须验证
    const MUST_FILTER = 3;
    //值是空时验证
    const EMPTY_FILTER = 4;
    //不存在字段时处理
    const NOT_EXIST_FILTER = 5;
    //--------处理时机/自动完成&自动验证共用
    //插入时处理
    const MODEL_INSERT = 1;
    //更新时处理
    const MODEL_UPDATE = 2;
    //全部情况下处理
    const MODEL_BOTH = 3;
    //允许填充字段
    protected $allowFill = [];
    //禁止填充字段
    protected $denyFill = [];
    //模型数据
    protected $data = [];
    //构建数据
    protected $original = [];
    //数据库连接
    protected $connect;
    //表名
    protected $table;
    //表主键
    protected $pk;
    //字段映射
    protected $map = [];
    //时间操作
    protected $timestamps = false;
    //数据库驱动
    protected $db;

    public function __construct($data = [])
    {
        //设置表名
        if (empty($this->table)) {
            $model       = basename(str_replace('\\', '/', get_class($this)));
            $this->table = strtolower(
                preg_replace('/.([A-Z])/', '_\1', $model)
            );
        }
        //数据驱动
        $this->db = Db::table($this->table);
        //表主键
        $this->pk = $this->pk ?: $this->db->getPrimaryKey();
        if ( ! empty($data)) {
            $this->create($data);
        }
    }

    /**
     * 获取表名
     *
     * @return string
     */
    public function getTable()
    {
        return $this->table;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * 设置data 记录信息属性
     *
     * @param array $data
     *
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * 对象数据转为数组
     *
     * @return array
     */
    final public function toArray()
    {
        return $this->data;
    }

    /**
     * 保存数据时的字段验证
     *
     * @param array $data
     */
    final private function fieldFillCheck(array $data)
    {
        if (empty($this->allowFill) && empty($this->denyFill)) {
            $data = [];
        }
        //允许填充的数据
        if ( ! empty($this->allowFill) && $this->allowFill[0] != '*') {
            $data = Arr::filterKeys($data, $this->allowFill, 0);
        }
        //禁止填充的数据
        if ( ! empty($this->denyFill)) {
            if ($this->denyFill[0] == '*') {
                $data = [];
            } else {
                $data = Arr::filterKeys($data, $this->denyFill, 1);
            }
        }
        $this->original = array_merge($this->original, $data);
        if (empty($this->original)) {
            throw new Exception('没有数据用于添加');
        }
    }

    /**
     * 批量设置做准备数据
     *
     * @param array $data
     *
     * @return $this
     */
    final private function create(array $data = [])
    {
        $this->fieldFillCheck($data);
        //更新时设置主键
        if ($this->action() == self::MODEL_UPDATE) {
            $this->original[$this->pk] = $this->data[$this->pk];
        }
        //修改时间
        if ($this->timestamps === true) {
            $this->original['updated_at'] = $this->getTime();
            if ($this->action() == self::MODEL_INSERT) {
                //更新时间
                $this->original['created_at'] = $this->getTime();
            }
        }

        return $this;
    }

    protected function getTime()
    {
        return date('Y-m-d h:i:s');
    }

    /**
     * 动作类型
     *
     * @return int
     */
    final public function action()
    {
        return empty($this->data[$this->pk]) ? self::MODEL_INSERT
            : self::MODEL_UPDATE;
    }

    /**
     * 更新模型的时间戳
     *
     * @return bool
     */
    final public function touch()
    {
        if ($this->action() == self::MODEL_UPDATE && $this->timestamps) {
            return Db::table($this->table)->where(
                $this->pk,
                $this->data[$this->pk]
            )->update(['updated_at' => $this->getTime()]);
        }

        return false;
    }

    /**
     * 更新或添加数据
     *
     * @param array $data 批量添加的数据
     *
     * @return bool
     * @throws \Exception
     */
    final public function save(array $data = [])
    {
        //批量设置数据
        $this->create($data);
        //自动完成/自动过滤/自动验证
        $this->autoOperation();
        $this->autoFilter();
        if ( ! $this->autoValidate()) {
            return false;
        }
        //更新条件检测
        $res = null;
        $db  = Db::table($this->table);
        switch ($this->action()) {
            case self::MODEL_UPDATE:
                if ($res = $db->update($this->original) && $this->pk) {
                    $this->data = $db->find($this->data[$this->pk]);
                }
                break;
            case self::MODEL_INSERT:
                if ($res = $db->insertGetId($this->original)) {
                    if (is_numeric($res) && $this->pk) {
                        $this->data = $db->find($res);
                    }
                }
                break;
        }
        $this->original = [];

        return $res;
    }

    /**
     * 删除数据
     *
     * @return bool
     */
    final public function destory()
    {
        //没有查询参数如果模型数据中存在主键值,以主键值做删除条件
        if ( ! empty($this->data[$this->pk])) {
            if ($this->db->delete($this->data[$this->pk])) {
                $this->setData([]);

                return true;
            }
        }

        return false;
    }

    /**
     * 获取模型值
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        if (isset($this->data[$name])) {
            return $this->data[$name];
        }
        //关键方法获取
        if (method_exists($this, $name)) {
            return $this->$name();
        }
    }

    /**
     * 设置模型数据值
     *
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->original[$name] = $value;
    }

    /**
     * 魔术方法
     *
     * @param $method
     * @param $params
     *
     * @return mixed
     */
    public function __call($method, $params)
    {
        $res = call_user_func_array([$this->db, $method], $params);

        return $this->returnParse($method, $res);
    }

    /**
     * 调用数据驱动方法
     *
     * @param $method
     * @param $params
     *
     * @return mixed
     */
    public static function __callStatic($method, $params)
    {
        return call_user_func_array([new static(), $method], $params);
    }

    protected function returnParse($method, $result)
    {
        if ( ! empty($result)) {
            switch (strtolower($method)) {
                case 'find':
                case 'first':
                    $instance = new static();

                    return $instance->setData($result);
                case 'get':
                    $Collection = Collection::make([]);
                    foreach ($result as $k => $v) {
                        $instance       = new static();
                        $Collection[$k] = $instance->setData($v);
                    }

                    return $Collection;
                case 'paginate':
                    return $result;
                default:
                    /**
                     * 返回值为查询构造器对象时
                     * 返回模型实例
                     */
                    if ($result instanceof Query) {
                        return $this;
                    }

                    return $result;
            }
        }

        return $result;
    }
}