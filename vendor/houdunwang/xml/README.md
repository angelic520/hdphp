#XML处理
###介绍
在 web 开发中经常大量用到 XML这种快速简便的数据传输格式,本组件可用于创建与解析XML。
[TOC]

#复杂的XML操作
###生成XML

```
$obj = new \houdunwang\xml\Xml();
$data = array(
	'@attributes' => array(
		'type' => 'fiction'
	),
	'book'        => array(
		array(
			'@attributes' => array(
				'author' => 'houdunwang.com'
			),
			'title'       => 'houdunwang'
		),
		array(
			'@attributes' => array(
				'author' => 'hdcms'
			),
			'title'       => array( '@cdata' => 'version' ),
			'price'       => '$998'
		)
	)
);
header( 'Content-Type: application/xml' );
$xml = $obj->toXml('root_node', $data);
echo $xml;
```

**生成结果**

```
<?xml version="1.0" encoding="UTF-8"?>
<root_node type="fiction">
  <book author="houdunwang.com">
    <title>houdunwang</title>
  </book>
  <book author="hdcms">
    <title><![CDATA[version]]></title>
    <price>$998</price>
  </book>
</root_node>
```

###解析XML
xml文件 hd.xml：
```
$obj = new \houdunwang\xml\Xml();
$xml=<<<str
<?xml version="1.0" encoding="UTF-8"?>
<root_node type="fiction">
	<book author="houdunwang.com">
		<title>houdunwang</title>
	</book>
	<book author="hdcms">
		<title><![CDATA[version]]></title>
		<price>$998</price>
	</book>
</root_node>
str;
$result = $obj->toArray($xml);
print_r($result);
```

#简单的XML操作

###生成xml字符
不能分析复杂的XML数据比如有属性的XML
```
$xml=['name'=>'houdunwang','url'=>'houdunwang.com'];
$obj = new \houdunwang\xml\Xml();
$obj->toSimpleXml($xml);
```

###解析XML
将xml转为array,不分析XML属性等数据
```
$obj = new \houdunwang\xml\Xml();
$obj->toSimpleArray($xml);
```