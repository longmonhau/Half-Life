<?php namespace lOngmon\Hau\core;

use Illuminate\Container\Container;  
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model  as Eloquent;
use lOngmon\Hau\core\component\Log;

class Model extends Eloquent
{
	private static $database = [];

	private static $modelHouse = [];

	public static function initCache()
	{
		if( is_file(APP_PATH.'/config/database.php') )
		{
			self::$database = include( APP_PATH.'/config/database.php' );

			$capsule = new Capsule;

			// 创建链接
			$capsule->addConnection(self::$database);

			// 设置全局静态可访问
			$capsule->setAsGlobal();

			// 启动Eloquent
			$capsule->bootEloquent();
		} else
		{
			Log::Error(" Config file \"".APP_PATH."/config/database.php\" dose not exist!");
			throw new \Exception("Database configure file dose not exist", 1);
		}
	}

	public function __Construct()
	{		
	}

	public static function make( $name )
	{
		if( isset(self::$modelHouse[$name]) )
		{
			return self::$modelHouse[$name];
		}

		$model_namespace = "lOngmon\Hau\usr\model";
		$modelName = $model_namespace.'\\'.$name.'Model';
		
		if( class_exists( $modelName ) )
		{
			return self::$modelHouse[$name] = new $modelName;
		} else
		{
			return new static();
		}
	}
}