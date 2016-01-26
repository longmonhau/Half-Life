<?php namespace lOngmon\Hau\core\http;

use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

class Session extends SymfonySession
{
	private static $instance = NULL;

	public static function newInstance()
	{
		if( self::$instance != NULL )
		{
			return self::$instance;
		}
		self::$instance = new static();
		return self::$instance;
	}
}