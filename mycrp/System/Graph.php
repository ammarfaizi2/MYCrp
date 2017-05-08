<?php
namespace System;
use Curl\CMCurl;

/**
* 
*/
class Graph
{
	private $token;
	public function __construct($token)
	{
		$this->token = $token;
	}
}