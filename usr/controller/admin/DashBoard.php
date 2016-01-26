<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;

class DashBoard extends Control
{
	public function index()
	{
		$this->display( "dashBoard.html" );
	}
}