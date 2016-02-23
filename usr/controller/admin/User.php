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
        $sess = Factory::make("session");
        if( stripos($this->server->HTTP_REFERER, "entrance") === false
        && stripos($this->server->HTTP_REFERER, "logout") === false
        && stripos($this->server->HTTP_REFERER, "login") === false )
        {
            $sess->set("http_referer", $this->server->HTTP_REFERER);
        }
        
		$this->display("sign.html");
	}

	public function login()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{

    		if( !$name = $this->post("name") )
    		{
    			return $this->renderJson(['code'=>400,'errmsg'=>'Missing required parameter:$username.']);
    		}
    		if( !$passwd = $this->post("passwd") )
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

    		tSession::login( $userObj, $this->server("HTTP_USER_AGENT") );
            $this->updateLoginInfo( $userObj, $this->server("REMOTE_ADDR"));

            $sess = Factory::make("session");
    		if( !$goRoute = $sess->get("http_referer") )
            {
                $Gor = Route::getRouteUri('dashBoard');
                $goRoute = $Gor[1];
                $sess->remove("http_referer");
            }
    		return $this->renderJson(['code'=>200, 'errmsg'=>'ok', 'go_url'=> $goRoute ]);
    	} else {
    		return $this->renderJson(["code"=>403, "errmsg"=>"Access forbindden"]);
    	}
		
	}

    public function logout()
    {
        tSession::clear();
        $goRoute = Route::getRouteUri("index");
        Route::redirect( $goRoute[1] );
    }

    private function updateLoginInfo( $userModel, $remote_ip )
    {
        $userModel->last_login_at = date("Y-m-d H:i:s");
        $userModel->last_login_ip = ip2long( $remote_ip );
        $userModel->save();
    }
}