<?php namespace lOngmon\Hau\usr\controller;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\core\bundle\Feed;

class Message extends Control
{
    use SiteInfo;
	public function index()
	{
        $this->assign("site", $this->getSiteInfo());
		return $this->display("message.html");
	}

	public function submit()
	{
	    if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
	      && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
	    {   
    		$msg = [];
    		$msg['name'] = strip_tags($this->post("name"));
    		$msg['email'] = strip_tags($this->post("email"));
    		$msg['gravatar'] = md5($msg['email']);
    		$msg['msgbody'] = strip_tags($this->post("text"));
            $msg['created_at'] = date("Y-m-d H:i:s");
    		$MessageModel = Model::make("Message");
    		$id = $MessageModel->insertGetId($msg);

    		/******************* 插入Feed ******************/
    		$feed = [];
    		$feed['name'] = $msg['name'];
    		$feed['email'] = $msg['email'];
    		$feed['gravatar'] = $msg['gravatar'];
    		$feed['content'] = $feed['name']."给你发来私信:<br/><a href='/Admin/Message/View/".$id.".html' style='font-size:14px;color:#0083D6;'>".mb_substr($msg['msgbody'], 0, 60,"utf-8")."</a>";
            $feed['created_at'] = date("Y-m-d H:i:s");
    		Feed::add( $feed );

    		/***************** Sending email asyn way **********/
    		popen("php ".APP_PATH."/task/MailTo.php -fmessageEmail -d".$id."&", "r");
    		return $this->renderJson(200,"ok");
    	} else
    	{
    		return $this->renderJson(403,"Access denied!");
    	}
	}
}
