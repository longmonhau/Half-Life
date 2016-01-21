<?php namespace lOngmon\Hau\core\component;

class Config
{
	private static $cfg = [];

	public static function init()
	{
		if( is_file(APP_PATH.'/config/common.php') )
		{
			self::$cfg = include(APP_PATH.'/config/common.php');
		}
	}

	/**
	 * get configure item
	 * @param  string $key 支持像a.b.c这样的层级配置项
	 * @param  mixed $val  默认值
	 * @return mixed 
	 */
	public static function get($key, $val = null)
	{
		$key_arr = explode(".", $key);
		$key_len = count($key_arr);
		$tmp = self::$cfg;
		for($i=0;$i<$key_len;$i++)
		{
			if(isset($tmp[$key_arr[$i]]))
			{
				$tmp = $tmp[$key_arr[$i]];
			} else
			{
				return $val;
			}
		}
		return $tmp;
	}

	public static function set($key, $val)
	{
		sefl::$cfg[$key] = $val;
		return true;
	}
}