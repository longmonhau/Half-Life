<?php namespace lOngmon\Hau\core;

use lOngmon\Hau\core\Factory;

class Control
{
	private $tpl_vars = [];

	private $response = NULL;

	private $request = NULL;

	protected $get = NULL;

	protected $post = NULL;

	protected $server = NULL;

	protected $AjaxRequest = fasle;

	public function __Construct()
	{
		$this->response = Factory::make("response");
		$this->request = Factory::make("request");
		$this->get = $this->post = $this->server = new \stdClass;
		
		foreach ($_GET as $k => $v ) 
		{
			$this->get->$k = $this->request->query->get($k);
		}

		foreach ($_POST as $p => $v) 
		{
			$this->post->$p = $this->request->get($p);
		}

		foreach ($_SERVER as $s => $v) 
		{
			$this->server->$s = $this->request->server->get($s);
		}

		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
    		$this->AjaxRequest = true;
    	}
	}

	protected function assign( $key, $val)
	{
		$this->tpl_vars[$key] = $val;
	}

	protected function display( $html )
	{
		return $this->response->toHtml( $html, $this->tpl_vars );
	}

	protected function renderJson( $arr, $val = NULL )
	{
		if( $val )
		{
			$arr = ["code"=>$arr, "errmsg"=>$val];
		}
		return $this->response->toJson( $arr );
	}

	protected function renderString( $str )
	{
		return $this->response->toString( $str );
	}

	/**
	 * 获取GET参数
	 * @param  string $key
	 * @return mixed
	 */
	protected function get( $key )
	{
		if( isset( $this->get->$key ) )
		{
			return $this->get->$key;
		}
		return null;
	}

	/**
	 * 获取POST参数
	 * @param  string
	 * @return mixed
	 */
	protected function post( $key )
	{
		if( isset( $this->post->$key ) )
		{
			return $this->post->$key;
		}
		return null;
	}

	/**
	 * 获取SERVER参数
	 * @param  string
	 * @return mixed
	 */
	protected function server($key)
	{
		if( isset( $this->server->$key ) )
		{
			return $this->server->$key;
		}
		return NULL;
	}

	public function notFound()
	{
		$this->display("error.html");
	}
}