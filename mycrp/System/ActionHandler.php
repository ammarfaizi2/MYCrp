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

	
	public function run()
	{
		$this->get_target();
		$this->get_data();
	}

}