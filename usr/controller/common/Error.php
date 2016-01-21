<?php namespace lOngmon\Hau\usr\controller\common;

use lOngmon\Hau\core\Control; 

class Error extends Control
{
	public function __Construct()
	{
		parent::__Construct();
	}

	public function _40x()
	{
		$this->display("error.html");
	}
}