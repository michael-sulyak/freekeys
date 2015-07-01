<?php
/*
Name: Index
Author: expert_m
Author URI: http://nextable.ru/
*/

require_once 'main.php';

$me = new ModularEngine();
$me->set_config('type', 'index', true);
$me->load_config_json('config.json');
$me->start();

?>