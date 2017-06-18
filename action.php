<?php
require __DIR__ . '/vendor/autoload.php';
define('data',__DIR__ . '/data');
define("fb_data", data."/fb_data");
is_dir(data) or mkdir(data);
is_dir(fb_data) or mkdir(fb_data);
use System\ActionHandler;
require __DIR__ . '/config/config.php';
foreach ($config as $val) {
	(new ActionHandler($val))->run();
}