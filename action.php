<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/config/config.php';
define('data',__DIR__ . '/data');
is_dir(data) or mkdir(data);
use System\ActionHandler;

foreach ($config as $val) {
	$app = new ActionHandler($val['user'],$val['token']);
	$app->run();
}