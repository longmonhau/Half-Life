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

	protected function assign( $key, $val)
	{
		$this->tpl_vars[$key] = $val;
	}

	protected function display( $html )
	{
		return $this->response->toHtml( $html, $this->tpl_vars );
	}

	protected function renderJson( $arr )
	{
		return $this->response->toJson( $arr );
	}

	protected function renderString( $str )
	{
		return $this->response->toString( $str );
	}

	public function notFound()
	{
		$this->display("error.html");
	}
}