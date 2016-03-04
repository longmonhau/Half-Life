<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;

class Works extends Control
{
	public function index()
	{
		return $this->display("adminWorks.html");
	}
}