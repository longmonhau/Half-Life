<?php namespace lOngmon\Hau\usr\midware;

use lOngmon\Hau\core\midware\AbstractMidWare;
use lOngmon\Hau\core\Factory;

class CommentTimeLimit extends AbstractMidWare
{
	private $Sess 	= NULL;
	private $Req 	= NULL;
	public function __construct()
	{
		$this->Sess 	= Factory::make("session");
		$this->Req 		= Factory::make("request");
	}

	protected function assert()
	{
		$remote_ip = $this->Req->server->get("REMOTE_ADDR");
		$user_agent = $this->Req->server->get("HTTP_USER_AGENT");
		$now = time();
		$ip_time = $this->Sess->get(md5($remote_ip));
		$user_agent_time = $this->Sess->get(md5($user_agent));
		//var_dump( $remote_ip,$ip_time, $user_agent_time, $now );
		if( !$ip_time && !$user_agent_time )
		{
			return true;
		}
		if( $ip_time === $user_agent_time && ($now-$ip_time) > 2*60 )
		{
			return true;
		}
		return false;
	}

	protected function next()
	{
		$remote_ip = $this->Req->server->get("REMOTE_ADDR");
		$user_agent = $this->Req->server->get("HTTP_USER_AGENT");
		$now = time();
		$this->Sess->set(md5($remote_ip), $now);
		$this->Sess->set(md5($user_agent), $now);
	}

	protected function falseHandler()
	{
		//$this->Sess->clear();
		exit(json_encode(["code"=>403,"errmsg"=>"请在两分钟后重试！"]));
	}
}
