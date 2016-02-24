<?php namespace lOngmon\Hau\core;

use lOngmon\Hau\core\component\tRedis;

class Dependency
{
	private static $service = [];

	private static $midware = [];

	public static function initCache()
	{
		if( empty(self::$service) )
		{
			self::serviceRegistry();
		}
		if( empty( self::$midware ) )
		{
			self::midWareRegistry();
		}
	}

	private static function serviceRegistry()
	{
		self::$service = array(
			"request" 	=> "lOngmon\Hau\core\http\Request",
			"response"	=> "lOngmon\Hau\core\http\Response",
			"session"   => "lOngmon\Hau\core\http\Session",
			"twig"      => "lOngmon\Hau\core\\template\TwigTemplate"
		);

		foreach ( self::$service as $name => $service ) 
		{
			tRedis::hset( "dependCache", $name, $service );
		}
	}

	private static function midWareRegistry()
	{
		self::$midware = array(
			"auth" 				=> "lOngmon\Hau\usr\midware\auth",
			"uploadAuth" 		=> "lOngmon\Hau\usr\midware\uploadAuth",
			"CommentTimeLimit" 	=> "lOngmon\Hau\usr\midware\CommentTimeLimit"
		);

		foreach (self::$midware as $name => $midware) 
		{
			tRedis::hset("midWareCache", $name, $midware );
		}
	}

	public static function getService( $name )
	{
		if( !$service =  tRedis::hget("dependCache", $name ) )
		{
			if( isset(self::$service[$name]) )
			{
				$service = self::$service[$name];
			}
		}
		return $service;
	}

	public static function MidWare( $name )
	{
		if( !$midware = tRedis::hget( "midWareCache", $name ) )
		{
			if( isset( self::$midware[$name] ) )
			{
				$midware = self::$midware[$name];
			}
		}
		if( $midware )
		{
			return new $midware;
		} else
		{
			return null;
		}
	}
}