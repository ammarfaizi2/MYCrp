<?php
namespace System;
use System\Graph;
/**
* 
*/
class ActionHandler
{
	
	public function __construct($user,$token)
	{
		$this->user  = $user;
		$this->graph = new Graph($token);
		$this->path = data.'/'.$this->user;
		is_dir($this->path) or mkdir($this->path);
	}

	private function get_target()
	{
		$this->target = file_exists($this->path.'/target.txt') ? explode("\n",file_get_contents($this->path.'/target.txt')) : array();
	}

	private function get_data()
	{
		$this->data = file_exists($this->path.'/data.txt') ? explode("\n",file_get_contents($this->path.'/data.txt')) : array();
	}

	private function check_newpost()
	{

	}

	private function gen_react()
	{
		$a = array("LOVE","WOW","LIKE");
		return $a[rand(0,2)];
	}


	public function run()
	{
		$this->get_target();
		$this->get_data();
		foreach ($this->target as $user) {
			$current = $this->graph->get_newpost($user);
			if (!isset($this->data[$user]) or !in_array($current, $this->data[$user])) {
				$ctn = isset($this->data[$user]) ? count($this->data[$user]) : 0;
				if ($ctn>5) {
					$this->data[$user][5] = $current;
				} else {
					$this->data[$user][] = $current;
				}
				$this->action[] = $this->graph->do_react($current,$this->gen_react());
			}
		}
		print_r($this->action);
	}

}