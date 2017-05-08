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
		$this->token  = $token;
		$this->_token = urlencode($token);
	}
	public function do_react($post_id,$type="LIKE")
	{
		$st = new CMCurl(self::G.$post_id.'/reactions?method=post&type='.urlencode($type).'&access_token='.$this->_token);
		$ot = $st->execute();
		print_r($ot);
		$st->close();
		return $ot;
	}
	public function get_newpost($userid="me",$token=null)
	{
		$st = new CMCurl(self::G.$userid.'/feed?limit=1&fields=id&access_token='.$this->_token);
		$ot = json_decode($st->execute(),true);
		$st->close();
		return isset($ot['data'][0]['id']) ? $ot['data'][0]['id'] : false;
	}
}