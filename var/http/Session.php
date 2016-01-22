<?php namespace lOngmon\Hau\core\http;

use Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

class Session
{
	private $session = NULL;

	public static function newInstance()
	{
		$self = new static();
		$self->session = new SymfonySession();
		return $self;
	}

	public function start()
	{
		$this->session->start();
	}
}