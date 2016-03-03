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
        $request_come_from = $this->cookie->hl_http_referer;
        if( $request_come_from != '' 
        && stripos($request_come_from, "entrance")  === false
        && stripos($request_come_from, "logout")    === false
        && stripos($request_come_from, "login")     === false )
        {
            $this->assign("httpReferer", $request_come_from);
        }
    
		$this->display("sign.html");
	}

	public function login()
	{
		if( $this->AjaxRequest )
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

            if( $http_referer = $this->post("http_referer") )
            {
                $go_url = $http_referer;
            } else
            {
                $go_url = "/admin/dashBoard.html";
            }
    		return $this->renderJson(['code'=>200, 'errmsg'=>'ok', 'go_url'=> $go_url ]);
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