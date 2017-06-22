<?php
require __DIR__ . '/vendor/autoload.php';
define('data',__DIR__ . '/data');
define("fb_data", data."/fb_data");
is_dir(data) or mkdir(data);
is_dir(fb_data) or mkdir(fb_data);
$config = array(
		"user"	=> "ammarfaizi2",
		"email" => "ammarfaizi2",
		"pass"	=> "454469123iceteafac",
	);

$fb = new Facebook\Facebook($config['email'], $config['pass'], $config['user']);
if (!$fb->check_login()) {
	$fb->login();
}

run($_GET['url']??"");

function run(string $url)
{
	global $fb;
		if (!$fb->check_login() && !((bool)count($_POST))) {
			$fb->login();
			if (isset($fb->curl_info['redirect_url']) && !empty($fb->curl_info['redirect_url'])) {
				print go($fb->curl_info['redirect_url']);
				die;
			}
		}
		print go(urldecode($url));
}

function go($url)
{
	global $fb;
	$post 	= count($_POST) ? $_POST : null;
	$header = getallheaders();
	var_dump($header);
	if (count($_POST) && $header['Content-Type']=="application/x-www-form-urlencoded") {
		$_p = "";
		foreach ($_POST as $key => $value) {
			if (is_array($value)) {
				foreach ($value as $k2 => $v2) {
					$_p .= $key.urlencode("[".$k2."]")."=".urlencode($v2)."&";
				}
			} else {
				$_p .= $key."=".urlencode($value)."&";
			}
		}
		$post = rtrim($_p, "&");
	}
	$src	= $fb->get_page($url, $post, array(52=>1));
	
	return clean($src);
}

function clean($src)
{
	$a		= explode("<form", $src);
	if (count($a)>1) {
		$r = array();
		foreach ($a as $val) {
			$b = explode("action=\"", $val, 2);
			if (count($b)>1) {
				$b = explode("\"", $b[1], 2);
				/*$r["action=\"".$b[0]."\""] = "action=\"?url=".urlencode(html_entity_decode($b[0], ENT_QUOTES, 'UTF-8'));*/
				$src = str_replace("action=\"".$b[0]."\"", "action=\"?url=".htmlspecialchars(urlencode(urlencode(html_entity_decode($b[0], ENT_QUOTES, 'UTF-8'))))."\"", $src);
			}
		}
	}
	$a = explode("<a ", $src);
	foreach ($a as $val) {
		$b = explode("href=\"", $val, 2);
		if (count($b)>1) {
			$b = explode("\"", $b[1], 2);
			/*$r["action=\"".$b[0]."\""] = "action=\"?url=".urlencode(html_entity_decode($b[0], ENT_QUOTES, 'UTF-8'));*/
			$src = str_replace("href=\"".$b[0]."\"", "href=\"?".(isset($_GET['user'])?"user=".$_GET['user']."&":"")."url=".htmlspecialchars(urlencode(urlencode(html_entity_decode($b[0], ENT_QUOTES, 'UTF-8'))))."\"", $src);
		}
	}
	return $src;
}