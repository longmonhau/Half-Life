<?php namespace lOngmon\Hau\usr\midware;

use lOngmon\Hau\core\midware\AbstractMidWare;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\core\http\Route;

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
		Route::redirect("sign");
		exit;
	}
}