<?php namespace lOngmon\Hau\usr\midware;

use lOngmon\Hau\core\midware\AbstractMidWare;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\core\http\Route;
use lOngmon\Hau\core\Factory;

class auth extends AbstractMidWare
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
		$req = Factory::make("request");
		if( HTTP_METHOD == "GET" ){
			setCookie("hl_http_referer", Route::get_currentUri(),time()+3600,"/");
		}
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
    		exit(json_encode(["code"=>503,"errmsg"=>"登陆已过期，请重新登陆","go_url"=>"/admin/entrance.html"]));
    	} else {
			Route::redirect("sign");
			exit;
    	}
	}
}