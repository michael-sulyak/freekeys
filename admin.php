<?php
/*
Name: Admin
Author: expert_m
Author URI: http://nextable.ru/
*/

require_once 'main.php';

$me = new ModularEngine();
$me->load_config_json('config.json');
$me->set_config('type', 'admin');
$me->set_config('theme_name', 'admin');
$me->start();

?>