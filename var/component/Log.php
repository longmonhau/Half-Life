<?php namespace lOngmon\Hau\core\component;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use lOngmon\Hau\core\component\Config;

class Log
{
	private static $Log = NULL;

	public static function newInstance()
	{
		if( self::$Log instanceof Logger )
		{
			return self::$Log;
		}

		self::$Log = new Logger( "kernel" );
		self::$Log->pushHandler( new StreamHandler( Config::get("LOG-PATH"), Logger::WARNING ) );

		return self::$Log;
	}

	public static function __callStatic( $func, $param )
	{
		$log = self::newInstance();
		if( method_exists( $log, $func ) )
		{
			return call_user_func_array([$log, $func], [$param]) ;
		} else
		{
			$log->addWarning("Calling undefined method: ". $log."::".$func);
		}
	}
}