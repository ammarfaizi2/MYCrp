<?php
namespace System;
use Facebook\Facebook;
use System\Graph;
/**
* 
*/
class ActionHandler
{
	private $user;
	private $path;
	private $data;
	private $graph;
	private $target;
	private $action;
	public function __construct($val)
	{
		$this->fb = new Facebook($val['email'], $val['pass'], $val['user']);
		$this->user  = $val['user'];
		$this->graph = new Graph($val['token']);
		$this->path = data.'/'.$this->user;
		is_dir($this->path) or mkdir($this->path);
		if (!$this->fb->check_login()) {
			$this->fb->login();
		}
	}

	private function get_target()
	{
		$get = file_exists($this->path.'/target.txt') ? explode("\n",file_get_contents($this->path.'/target.txt')) : array();
		$this->target = array();
		foreach ($get as $val) {
			$a = explode("=", $val);
			$this->target[trim($a[0])] = isset($a[1]) ? explode(",",$a[1]) : false;
		}
	}

	private function get_data()
	{
		$this->data = file_exists($this->path.'/data.txt') ? json_decode(file_get_contents($this->path.'/data.txt'),true) : array();
	}

	private function check_newpost()
	{

	}

	private function gen_react($a)
	{
		$a=$a===false?array("LOVE","WOW","LIKE"):$a;
		return $a[rand(0,count($a)-1)];
	}

	private function save_data()
	{
		file_put_contents($this->path.'/data.txt', json_encode($this->data,128));
	}
	public function run()
	{
		header('Content-type:application/json');
		$this->get_target();
		$this->get_data();
		foreach ($this->target as $user => $react_list) {
			$current = $this->graph->get_newpost($user);
			if (!isset($this->data[$user][$current])) {
				$ctn = isset($this->data[$user]) ? count($this->data[$user]) : 0;
				$this->action[$user] = date("Y-m-d H:i:s");
				$this->fb->reaction(substr($current, strpos($current, "_")+1),$this->gen_react($react_list));
				$this->data[$user][$current] = $this->action[$user];
			}
		}
		print json_encode($this->action);
		$this->save_data();
	}

}