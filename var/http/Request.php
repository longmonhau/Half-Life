<?php namespace lOngmon\Hau\core\http;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class Request
{
	public static function newInstance()
	{
		return SymfonyRequest::createFromGlobals();
	}
}
