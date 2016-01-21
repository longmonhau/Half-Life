<?php namespace lOngmon\Hau\core\component;

class tRedis
{
	private static $redisInstance = null;

	public static function newInstance()
	{
		if( !REDIS_ENABLE ) return NULL;

		if( self::$redisInstance instanceof  \Redis )
		{
			return self::$redisInstance;
		}
		$redis = [
			"host" => "127.0.0.1",
			'port'  => 6379
		];
		if( is_file(APP_PATH.'/config/redis.php' ) )
		{
			$redis = include(APP_PATH.'/config/redis.php');
		}
		if(!class_exists('\Redis'))
		{
			return null;
		}
		self::$redisInstance = new \Redis();
		if( !self::$redisInstance->connect($redis['host'], $redis['port']) )
		{
			self::$redisInstance = null;
		}
		return self::$redisInstance;
	}

	public static function __callStatic( $func, $param = [] )
	{
		if( !self::newInstance() instanceof  \Redis )
		{
			return NULL;
		}
		return call_user_func( [self::$redisInstance, $func], $param );
	}

	private function __construct()
	{
		//do nothing
	}
	private function __clone()
	{
		return null;
	}
}