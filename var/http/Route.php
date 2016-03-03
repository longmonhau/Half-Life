<?php namespace lOngmon\Hau\core\http;

use lOngmon\Hau\core\component\tRedis;
use lOngmon\Hau\core\Factory;

class Route
{
	private static $route_array = 	[];

	private $fastRoute 			= 	null;

	private static $current_uri = 	'';

	public static function initCacheRoute()
	{
		if( $redisRoute = tRedis::get('RoutesCache') )
		{
			self::$route_array = json_decode( $redisRoute , true);
			return;
		}

		if( is_file(APP_PATH.'/config/routes.php') )
		{
			self::$route_array = include APP_PATH.'/config/routes.php';
		}

		tRedis::set("RoutesCache", json_encode(self::$route_array));
		return;
	}

	public static function newInstance()
	{
		$self = new static();
		$self->fastRoute = \FastRoute\SimpleDispatcher(
		function( \FastRoute\RouteCollector $R)
		{
			foreach (self::$route_array as $name=>$route) 
			{
				$R->addRoute($route[0], strtolower($route[1]), $route[2]);
			}
		});
		return $self;
	}

	public function dispatch( $uri, $method )
	{
		self::$current_uri = $uri;
		(bool)$uri or $uri = '/';
		$uri = strtolower( $uri );
		$info = $this->fastRoute->dispatch( $method, urldecode( $uri ));
		//var_dump( $info );
		switch ($info[0]) 
		{
			case \FastRoute\Dispatcher::FOUND:
				return $this->_dispatch( $info[1], $info[2] );
				break;
			case \FastRoute\Dispatcher::NOT_FOUND:
				if( $uri == '/' ) $uri = '/Index/Index';
				$uri = trim( $uri, "/" );
				$uri_array = explode('/', $uri);
				if( count( $uri_array ) < 2 )
				{
					$uri_array[] = "index";
				}
				return $this->_dispatch( $uri_array, [] );
				break;
			case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
				return $this->_dispatch([["common\Error","_40x"]]);
				break;
		}
	}

	private function _dispatch( $callback, array $param = array() )
	{
		$route_result = [];
		if( is_array($callback) ){
			if( class_exists( CTRL_NAMESPACE."\\".$callback[0]) )
			{
				$route_result['controller'] = CTRL_NAMESPACE."\\".$callback[0];
				$route_result['action'] = $callback[1];
				if ( isset( $callback[2] ) )
				{
					$route_result['midware'] = $callback[2];
				}
			} else {
				$route_result['controller'] = CTRL_NAMESPACE.'\common\Error';
				$route_result['action'] = "_40x";
			}
		}
		$route_result['param'] = $param;
		return $route_result;
	}

	public static function getRouteUri( $name )
	{
		if( isset(self::$route_array[$name] ) )
		{
			return self::$route_array[$name];
		} else
		{
			return null;
		}
	}

	public static function redirect( $name )
	{
		if( isset( self::$route_array[$name] ) )
		{
			$route = self::$route_array[$name];	
			$name = $route[1];
		}
		header("HTTP 1.1/ 301");
		header("location: ". $name );
	}

	public static function get_currentUri()
	{
		return self::$current_uri;
	}
}