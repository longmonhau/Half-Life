<?php namespace lOngmon\Hau\usr\controller;

use lOngmon\Hau\core\Control;

class Index extends Control
{
	public function index()
	{
		$this->display("index.html");
	}
}