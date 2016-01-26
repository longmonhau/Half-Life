<?php namespace lOngmon\Hau\core\http;

use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use lOngmon\Hau\core\template\SimpleTemplate;

class Response
{
	private $response = null;

	public static function newInstance()
	{
		$self = new static();
		$self->response = new SymfonyResponse;
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

	public function toHtml( $html, $vars = [] )
	{
		$this->response->headers->set('Content-Type', 'text/html');
		$template = new SimpleTemplate();
		$template->assign( $vars );
		$htmls = $template->display( $html );
		$this->response->setContent( $htmls );
		$this->response->send();
	}
}