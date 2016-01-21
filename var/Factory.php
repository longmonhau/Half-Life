<?php namespace lOngmon\Hau\core;

use lOngmon\Hau\core\Dependency;

class Factory
{
	private static $dependency = [];

	public static function make( $name, $param = [] )
	{
		if( $service = Dependency::getService($name) )
		{
			if( empty($param) )
			{
				return call_user_func([$service, "newInstance"]);
			} else
			{
				return call_user_func_array( [$service, "newInstance"], $param );
			}
		}
	}
}