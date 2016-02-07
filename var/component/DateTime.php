<?php namespace lOngmon\Hau\core\component;

class DateTime
{
	public static function humanLook( $time )
	{
		if( preg_match('/[^\d]+/', $time) )
		{
			$time = strtotime($time);
		}
		if( !$time )
		{
			return "long time ago";
		}
		$now = time();
		$timeL = $now-$time;

		if( $timeL < 60 )
		{
			return $timeL."s";
		}
		if( $timeL < 60*60 )
		{
			return floor($timeL/60)."m";
		}
		if( $timeL < 60*60*24 )
		{
			return floor($timeL/60/60)."h";
		}
		if( $timeL < 60*60*24*30 )
		{
			return floor($timeL/60/60/24)."d";
		}
	}
}