<?php namespace lOngmon\Hau\core\http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use lOngmon\Hau\core\template\SimpleTemplate;

class Response
{
	private $response = null;

	private $template = NULL;

	private $html = '';

	public static function newInstance()
	{
		$self = new static();
		$self->response = new SymfonyResponse;
		$self->template = new SimpleTemplate;
		return $self;
	}

	public function toString( $string )
	{
		$this->response->headers->set('Content-Type', 'text/plain');
		$this->response->setContent( $string );
		$this->response->send();
	}

	public function toJson( $arr )
	{
		if( !(is_array( $arr ) || is_object( $arr ) ) )
		{
			$arr = ["_content"=>$arr];
		}
		$this->response->headers->set("Content-Type","application/json");
		$this->response->setContent( json_encode( $arr, JSON_UNESCAPED_UNICODE ) );
		$this->response->send();
	}

	public function renderHtml( $html, $vars = [] )
	{
		$this->response->headers->set('Content-Type', 'text/html');	
		$this->template->assign( $vars );
		$this->html = $this->template->display( $html );
		$this->response->setContent( $this->html );
		$this->response->send();
	}

	public function toHtml($html,$vars, $fileName)
	{
		$this->renderHtml($html, $vars, $fileName );
		$this->template->makeHtml($this->html, $fileName);
	}
}