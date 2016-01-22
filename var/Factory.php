<?php namespace lOngmon\Hau\core;

use lOngmon\Hau\core\Dependency;

class Factory
{
	private static $dependency = [];

	private static $wareHouse = [];

	public static function make( $name, $param = [] )
	{	
		$name = strtolower($name);

		if( isset(self::$wareHouse[$name.join(":", $param)]) )
		{
			return self::$wareHouse[$name.join(":", $param)];
		}

		if( !$service = Dependency::getService($name) )
		{
			Log::Error("The service \"$name\" dose not exist!");
			throw new \Exception("The service \"$name\" dose not exist!", 1);
			
		}
		if( !class_exists( $service ) )
		{
			Log::Error("Class \" $service\" dose not exist!");
			throw new \Exception("Class \" $service\" dose not exist!", 1);
			
		}
		$reflectClass = new \ReflectionClass($service);

		if( $reflectClass->hasMethod("newInstance") )
		{	
			if( empty($param) )
			{
				return self::$wareHouse[$name] = call_user_func([$service, "newInstance"]);
			} else
			{
				return self::$wareHouse[$name.join(":",$param)] = call_user_func_array( [$service, "newInstance"], $param );
			}
		}

		if( $constructor = $reflectClass->hasMethod("__construct") )
		{
			if( $constructor->isPublic() )
			{
				return self::$wareHouse[$name.join(":",$param)] = $reflectClass->newInstanceArgs( $param );
			}
		}
		throw new \Exception("Class \" $service \" has no public contructor ", 1);
		
	}

	public static function enforceMake( $name, $cache = false )
	{
		$name = strtolower($name);

		if( !$service = Dependency::getService($name) )
		{
			Log::Error("The service \"$name\" dose not exist!");
			throw new \Exception("The service \"$name\" dose not exist!", 1);
			
		}
		if( !class_exists( $service ) )
		{
			Log::Error("Class \" $service\" dose not exist!");
			throw new \Exception("Class \" $service\" dose not exist!", 1);
			
		}

		$reflectClass = new \ReflectionClass($name);

		$instance = $reflectClass->newInstanceWithoutConstructor();

		if( $cache )
		{
			self::$wareHouse[$name] = $instance;
		} 
		return $instance;
	}
}