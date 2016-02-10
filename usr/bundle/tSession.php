<?php namespace lOngmon\Hau\usr\bundle;

use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\core\bundle\tPassword;
use lOngmon\Hau\core\component\tString;

class tSession
{
	public static function login( $user, $userAgent )
	{

		$userInfo 			= [];
		$userInfo['id'] 	= $user->id;
		$userInfo['name'] 	= $user->name;
		$userInfo['sname']	= $user->sname;
		$userInfo['email'] 	= $user->email;
		$userInfo['role'] 	= $user->role;

		$userInfoJson = json_encode( $userInfo );
		$session = Factory::make( 'session' );
		$session->set( "_logined_user", $userInfoJson );
		$user_login_key = tString::rand( 12, tString::ALPHA );
		$user_login_val = $userInfoJson.$userAgent;
		$user_login_val_hash = tPassword::hash($user_login_val);
		$session->set( "_login_cookie_key",$user_login_key );
		$session->set( "_login_cookie_val", $user_login_val );
		setCookie( $user_login_key, $user_login_val_hash, time()+3600,'/');
	}

	public static function update()
	{
		$session = Factory::make( 'session' );
		//=========== get old cookie key in session ==================
		$user_login_key_old = $session->get("_login_cookie_key");

		//============ get cookie val in session =====================
		$user_login_val = $session->get("_login_cookie_val");

		//============= product a new cookie key =====================
		$user_login_key = tString::rand( 12, tString::ALPHA );

		//============== product new cookie val ======================
		$user_login_val_hash = tPassword::hash($user_login_val);

		//============== store the new cookie in session ============
		$session->remove("_login_cookie_key");
		$session->set( "_login_cookie_key", $user_login_key );

		//============== set the new cookie with new key & val =====
		setCookie( $user_login_key, $user_login_val_hash, time()+3600, '/');

		//============== expire the old cookie ======================
		setCookie( $user_login_key_old, '', time()-1,'/');

	}

	public static function verifyLoginStatus( $userAgent )
	{
		$session = Factory::make( 'session' );
		$user_login_key = $session->get("_login_cookie_key");
		$user_login_val = $session->get("_logined_user").$userAgent;
		if( !isset( $_COOKIE[$user_login_key] ) )
		{
			return false;
		}
		return tPassword::verify( $user_login_val, $_COOKIE[$user_login_key]);
	}

	public static function getLoginedUserInfo()
	{
		$session = Factory::make( 'session' );
		return json_decode($session->get("_logined_user"));
	}

	public static function clear()
	{
		$session = Factory::make( 'session' );
		$user_login_key_old = $session->get("_login_cookie_key");
		setCookie( $user_login_key_old, '',time()-1,'/');
		$session->clear();

	}
}