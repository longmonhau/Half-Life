<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
class Message extends Control
{
	public function index()
	{
		return $this->display("adminMessge.html");
	}

	public function view()
	{
		//
	}
}