<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\core\http\Route;
use lOngmon\Hau\core\bundle\tPassword;
use lOngmon\Hau\usr\bundle\tSession;

class User extends Control
{
	public function sign()
	{
		$this->display("sign.html");
	}

	public function login()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
    		$request = Factory::make("request");

    		if( !$name = $request->get("name") )
    		{
    			return $this->renderJson(['code'=>400,'errmsg'=>'Missing required parameter:$username.']);
    		}
    		if( !$passwd = $request->get("passwd") )
    		{
    			return $this->renderjson(['code'=>400,'errmsg'=>"Missing required parameter: $password"]);
    		}

    		$userModel = Model::make("User");
    		if( !$userObj = $userModel->getUserByName( $name ) )
    		{
    			return $this->renderJson(['code'=>401, "errmsg"=>"Incorrect password input"]);
    		}
    		//var_dump( $passwd, $userObj->passwd );
    		if( !tPassword::verify( $passwd, $userObj->passwd ) )
    		{
    			return $this->renderJson(['code'=>401,'errmsg'=>"incorrect password input"]);
    		}

    		tSession::login( $userObj, $request->server->get("HTTP_USER_AGENT") );
    		$goRoute = Route::getRouteUri("dashBoard");
    		return $this->renderJson(['code'=>200,'errmsg'=>'ok', 'go_url'=> $goRoute[1] ]);

    	} else {
    		return $this->renderJson(["code"=>403, "errmsg"=>"Access forbindden"]);
    	}
		
	}
}