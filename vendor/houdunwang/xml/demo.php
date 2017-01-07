<?php
require 'vendor/autoload.php';
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
