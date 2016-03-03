<?php namespace lOngmon\Hau\core;

use lOngmon\Hau\core\Factory;

class Control
{
	private $tpl_vars 		= 	[];

	private $response 		= 	NULL;

	private $request 		= 	NULL;

	protected $get 			= 	NULL;

	protected $post 		= 	NULL;

	protected $server 		= 	NULL;

	protected $header 		= 	NULL;

	protected $cookie 		= 	NULL;

	protected $AjaxRequest 	= 	false;

	public function __Construct()
	{
		$this->response = Factory::make("response");
		$this->request 	= Factory::make("request");
		$this->get 		= new \stdClass;
		$this->post 	= new \stdClass;
		$this->server 	= new \stdClass;
		$this->header 	= new \stdClass;
		$this->cookie 	= new \stdClass;
		
		foreach ($this->request->query as $k => $v ) 
		{
			$this->get->$k = $v;
		}
		foreach ($this->request->request as $p => $v) 
		{
			$this->post->$p = $v;
		}

		foreach ($this->request->server as $s => $v) 
		{
			$this->server->$s = $v;
		}

		foreach ($this->request->headers as $k => $v) 
		{
			$this->header->$k = $v;
		}

		foreach ($this->request->cookies as $k => $v) 
		{
			$this->cookie->$k = $v;
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
		return $this->response->renderHtml( $html, $this->tpl_vars );
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

	protected function toHtml($html,$fileName)
	{
		$this->response->toHtml($html, $this->tpl_vars, $fileName );
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