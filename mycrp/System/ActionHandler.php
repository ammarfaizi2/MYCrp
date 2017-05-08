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
	}

	public function get_target()
	{
		$this->target = file_exists(data.'/'.$this->user.'_target.txt') ? explode("\n",file_get_contents(data.'/'.$this->user.'_target.txt')) : array();
	}

	public function run()
	{

	}

}