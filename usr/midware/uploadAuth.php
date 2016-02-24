<?php namespace lOngmon\Hau\usr\midware;

use lOngmon\Hau\core\midware\AbstractMidWare;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\core\http\Route;

class uploadAuth extends AbstractMidWare
{
	protected function assert()
	{
		return tSession::verifyLoginStatus($_SERVER['HTTP_USER_AGENT']);
	}
	protected function next()
	{
		tSession::update();
	}
	protected function falseHandler()
	{
		tSession::clear();
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
    		exit(json_encode(["success"=>0,"message"=>"登陆已过期！请重新登陆后操作！"]));
    	} else {
			Route::redirect("sign");
			exit;
    	}
	}
}