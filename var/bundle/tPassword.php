<?php namespace lOngmon\Hau\core\bundle;

class tPassword
{
	public static function hash( $passwd, $alg = \PASSWORD_DEFAULT )
	{
		return \password_hash($passwd, $alg );
	}

	public static function verify( $passwd, $hash )
	{
		return \password_verify( $passwd, $hash );
	}

	public static function reflesh(){}
}
