<?php
require 'vendor/autoload.php';
$obj = new \houdunwang\collection\Collection();
$obj->make(['name'=>'后盾人']);
print_r($obj['name']);