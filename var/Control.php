<?php namespace lOngmon\Hau\core;

use lOngmon\Hau\core\Factory;

class Control
{
	private $tpl_vars = [];

	private $response = NULL;

	public function __Construct()
	{
		$this->response = Factory::make("response");
	}

	public function assign( $key, $val)
	{
		$this->tpl_vars[$key] = $val;
	}

	public function display( $html )
	{
		return $this->response->toHtml( $html, $this->tpl_vars );
	}

	public function notFound()
	{
		$this->render("error.html");
	}
}