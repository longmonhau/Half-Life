<?php namespace lOngmon\Hau\core\template;

use lOngmon\Hau\core\component\Config;
use lOngmon\Hau\core\Factory;

class SimpleTemplate
{
	private $engine = NULL;

	private $_vars = [];

	public function __Construct()
	{
		$engine = Config::get("TEMPLATE-ENGINE");
		$this->engine = Factory::make($engine);
	}

	public function assign( $key, $val = [] )
	{
		$this->engine->assign($key,$val);
	}

	public function display( $html )
	{
		return $this->engine->display($html);
	}
}