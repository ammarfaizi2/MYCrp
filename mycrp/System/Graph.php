<?php
namespace System;
use Curl\CMCurl;

/**
* 
*/
class Graph
{	
	const G = 'https://graph.facebook.com/';
	private $token;
	public function __construct($token)
	{
		$this->token = $token;
	}
	public function do_react($post_id,$type="LIKE")
	{
		$st = new CMCurl(self::G.$post_id'/reactions?type='.urlencode($type));
	}
}