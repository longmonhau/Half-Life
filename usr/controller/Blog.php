<?php namespace lOngmon\Hau\usr\controller;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\bundle\SlideBar;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\traits\AdminInfo;

class Blog extends Control
{
	use AdminInfo;
	public function view($url)
	{
		$PostModel = Model::make('Post');
		$Post = $PostModel->where("url", $url)->first();

		$this->assign("post", $Post);
		$this->assign("category", SlideBar::getCategoryAll());
		$this->assign("tags",SlideBar::getTags());
		$this->display("blogView.html");
	}

	public function comment()
	{
		$request = Factory::make("request");
		$postId = $request->get("postId");
		$commentModel = Model::make("Comment");
		$comments = $commentModel->getCommentsBypostId($postId);
		return $this->renderJson(["code"=>200,"comments"=> iterator_to_array($comments)]);
	}

	public function commentPost()
	{
		$request = Factory::make("request");
		$Rep = [];
		if( !$Rep['postId'] = $request->get("postId") )
		{
			return $this->renderJson(['code'=>400,'errmsg'=>"Missing required parameter:postId"]);
		}

		$PostModel = Model::make('Post');
		if( !$post = $PostModel->getPostById( $Rep['postId']) )
		{
			return $this->renderJson(['code'=>402,"errmsg"=>"Post dose not exists!"]);
		}
		if( !$Rep['name'] = $request->get("name") )
		{
			$Rep['name'] = "unnamed";
		}
		if( !$Rep['email'] = $request->get("email") )
		{
			return $this->renderJson(['code'=>400,'errmsg'=>"Missing required parameter:email"]);
		}
		$Rep['gravatar'] = md5($Rep['email']);
		if( !$Rep['content'] = $request->get("text") )
		{
			return $this->renderJson(['code'=>400,'errmsg'=>"Comment text can not be blank!"]);
		}

		$commentModel = Model::make("Comment");
		if( $commentModel->insertGetId($Rep) )
		{
			$Rep['receiver'] = $this->getAdminEmail();
			$Rep["post"] = ["title"=>$post->title,"url"=>$post->url];
			popen("php ".APP_PATH.'/task/MailTo.php -fcommentEmail -d'.base64_encode(json_encode($Rep))."&", "r");
		}
		return $this->renderJson(['code'=>200,'errmsg'=>"ok"]);
	}
}