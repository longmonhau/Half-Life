<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\core\bundle\tPassword;

class Profile extends Control
{
	public function index()
	{

		$user = tSession::getLoginedUserInfo();

		$this->assign("user", $user);
		return $this->display("profile.html");
	}

	public function submit()
	{
		
		if( $this->AjaxRequest )
		{
			$user = tSession::getLoginedUserInfo();
			$userModel = Model::make("User");
			$userObj = $userModel->getUserById($user->id);

			if( $sname = strip_tags($this->post("sname")) )
			{
				$userObj->sname = $user->sname = $sname;
			}
			if( $email = strip_tags($this->post("email")) )
			{
				$userObj->email = $user->email = $email;
			}
			if( $avatar = strip_tags( $this->post( 'avatar') ) )
			{
				$userObj->avatar = $user->avatar = $avatar;
			}

			$userObj->save();
			tSession::login($user, $this->server("HTTP_USER_AGENT"));

			return $this->renderJson(200,"ok");
		}
	}

	public function chpwd()
	{
		if( $this->AjaxRequest )
		{
			$user = tSession::getLoginedUserInfo();
			$userModel = Model::make("User");
			$userObj = $userModel->getUserById($user->id);

			if( !$old = $this->post("old") )
			{
				return $this->renderJson(400,"Missing required parameter:old password!");
			}
			if( !$newPwd = $this->post("new") )
			{
				return $this->renderJson(400,"Missing requried parameter:new password");
			}
			if( !$confirm = $this->post("confirm") )
			{
				return $this->renderJson(400,"Missing requred parameter:confirm password!");
			}
			if( $newPwd !== $confirm )
			{
				return $this->renderJson(400,"两次新密码输入不相同！");
			}
			if( !tPassword::verify($old, $userObj->passwd ) )
			{
				return $this->renderJson(400,"原密码不正确！");
			}
			$userObj->passwd = tPassword::hash($newPwd);
			$userObj->save();
			return $this->renderJson(["code"=>200, "errmsg"=>"ok"]);
		}
	}
}