<?php
require 'vendor/autoload.php';
$config = [
	//缓存表字段
	'cache_field' => true,
	//表字段缓存目录
	'cache_dir'   => 'storage/field',
	//读库列表
	'read'        => [ ],
	//写库列表
	'write'       => [ ],
	//开启读写分离
	'proxy'       => false,
	//主机
	'host'        => 'localhost',
	//类型
	'driver'      => 'mysql',
	//帐号
	'user'        => 'root',
	//密码
	'password'    => 'admin888',
	//数据库
	'database'    => 'demo',
	//表前缀
	'prefix'      => ''
];
\houdunwang\config\Config::set( 'database', $config );
class News extends \houdunwang\model\Model {
	//数据表
//	protected $table = "news";
	//允许填充字段
	protected $allowFill = [ '*' ];
	//禁止填充字段
	protected $denyFill = [ ];
	//自动验证
	protected $validate = [
		//['字段名','验证方法','提示信息',验证条件,验证时间]
	];

	//自动完成
	protected $auto= [
			//['字段名','处理方法','方法类型',验证条件,验证时机]
		];
	//自动过滤
	protected $filter= [
			//[表单字段名,过滤条件,处理时间]
		];
	public function one() {
		//逻辑...
		$this->error = "数据格式错误";
		return false;
	}
	public function findOne() {
		//业务逻辑
		$this->error = '数据类型不对';
		return false;
	}
	//时间操作,需要表中存在 created_at , updated_at 字段
	protected $timestamps = true;
}

$obj = new News();
$d = $obj->get();
print_r($d->toArray());