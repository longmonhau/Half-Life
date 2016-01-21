<?php namespace lOngmon\Hau\core;

use Illuminate\Container\Container;  
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model  as Eloquent;
use lOngmon\Hau\core\component\Log;

class Model extends Eloquent
{
	private static $database = [];

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
		}
	}

	public function __Construct()
	{		
	}
}