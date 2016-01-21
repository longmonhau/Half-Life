<?php namespace lOngmon\Hau\core;

use lOngmon\Hau\core\http\Request;
use lOngmon\Hau\core\http\Route;
use lOngmon\Hau\core\Dependency;
use lOngmon\Hau\core\component\Config;
use lOngmon\Hau\core\component\tRedis;
use lOngmon\Hau\core\component\Log;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\core\Model;

class Kernel
{
	private $request = null;

	private $route = null;

	public static function boot()
	{
		if ( version_compare( PHP_VERSION, 5.5, "<" ) ) 
		{
            			exit( "PHP required 5.5+" );
        		}

        		set_exception_handler( array(__CLASS__, 'panic') );

        		$redis = tRedis::newInstance();

        		Route::initCacheRoute( $redis );

        		Config::init();

        		Dependency::initCache();

        		Model::initCache();

		$self = new static();
		$self->route = Route::newInstance();
		$self->request = Request::newInstance();

		return $self;
	}

	public function run()
	{
		$pathInfo = $this->request->getPathInfo();
		$httpMethod = $this->request->server->get('REQUEST_METHOD');
		$pathInfo = str_replace(Config::get("URL-SUFFIX"), '', $pathInfo);
		$pathInfo = rtrim($pathInfo,"/");
		$route_ret = $this->route->dispatch( $pathInfo, $httpMethod );
		return $this->execute( $route_ret );
	}

	private function execute( $route )
	{
		if( method_exists( $route["controller"], $route["action"]) )
		{
			if( isset( $route['midware'] ) ) $this->midWare( $route['midware'] );

			$ctrl = new $route["controller"];

			if( isset($route['param']) )
			{
				return call_user_func( [$ctrl, $route["action"] ], $route['param'] );
			} else
			{
				return call_user_func( [$ctrl, $route["action"] ]);
			}
		} else 
		{
			return call_user_func( [$route['controller'], "notFound"]);
		}
	}

	private function midWare( $midwares )
	{
		foreach ($midwares as $name) 
		{
			if( $midware = Dependency::getMidWare( $name ) )
			{
				return $midware->run();
			} else
			{
				Log::Error("Calling undefined midware: ". $name);
				return;
			}
		}
	}

	public static function panic( \exception $e )
	{
		$response = Factory::make("response");
		$vars = [];
		$vars['message'] = $e->getMessage();
		$vars['file_line']  = $e->getFile()." ".$e->getLine();
		$response->toHtml( "exception.html", $vars );
	}
}