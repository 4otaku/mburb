<?php

include_once 'framework/init.php';

define('API_LIBS', ROOT_DIR . SL . 'api' . SL . 'libs' . SL);

Autoload::init(array(LIBS, EXTERNAL, API_LIBS,
	FRAMEWORK_LIBS, FRAMEWORK_EXTERNAL), CACHE);

mb_internal_encoding('UTF-8');

Config::parse(CONFIG . SL . 'define.ini', true);

$url = explode('/', preg_replace('/\?[^\/]+$/', '', $_SERVER['REQUEST_URI']));
$url = array_filter($url);
if (empty($url)) {
	$url = array('index');
}

$module = reset($url);

$class = 'Module_' . ucfirst($module);
if (!class_exists($class)) {
	$class = 'Module_Error';
}

$worker = new $class($url);
$worker->send_headers()->send_output();

