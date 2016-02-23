<?php namespace lOngmon\Hau\usr\controller;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\bundle\SlideBar;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\traits\AdminInfo;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\usr\bundle\tSession;

class Blog extends Control
{
	use AdminInfo;
	use SiteInfo;
	public function view($url)
	{
		$PostModel = Model::make('Post');
		$Post = $PostModel->where("url", $url)->first();

		$this->assign("post", $Post);
		$this->assign("category", SlideBar::getCategoryAll());
		$this->assign("tags",SlideBar::getTags());
		$this->assign("Site",$this->getSiteInfo());
		if( $loginedUser = tSession::getLoginedUserInfo() )
		{
			$this->assign("adminlogined", true);
			$this->assign("loginedUserName", $loginedUser->sname);
		}
		$this->display("blogView.html");
	}

	public function comment()
	{
		if( !$postId = intval($this->post("postId")) )
		{
			return $this->renderJson(400, "Invalid input post id!");
		}
		$commentModel = Model::make("Comment");
		$comments = $commentModel->where("resp",0)->where("postId", $postId)->get();
		foreach ($comments as $k => $comment)
		{
			$comments[$k]["subcomment"] = iterator_to_array($comment->getSubComments( $comment->id ));
		}
		return $this->renderJson(["code"=>200,"comments"=> iterator_to_array($comments)]);
	}

	public function commentPost()
	{
		$Rep = [];
		if( !$Rep['postId'] = $this->post("postId") )
		{
			return $this->renderJson(['code'=>400,'errmsg'=>"Missing required parameter:postId"]);
		}

		$PostModel = Model::make('Post');
		if( !$post = $PostModel->getPostById( $Rep['postId']) )
		{
			return $this->renderJson(['code'=>402,"errmsg"=>"Post dose not exists!"]);
		}
		if( !$Rep['name'] = $this->post("name") )
		{
			$Rep['name'] = "unnamed";
		}
		if( !$Rep['email'] = $this->post("email") )
		{
			return $this->renderJson(['code'=>400,'errmsg'=>"Missing required parameter:email"]);
		}
		$Rep['gravatar'] = md5($Rep['email']);
		if( !$Rep['content'] = $this->post("text") )
		{
			return $this->renderJson(['code'=>400,'errmsg'=>"Comment text can not be blank!"]);
		}
		$Rep['created_at'] = date("Y-m-d H:i:s");

		$commentModel = Model::make("Comment");
		if( $cid = $commentModel->insertGetId($Rep) )
		{
			$Rep['receiver'] = $this->getAdminEmail();
			$Rep["post"] = ["title"=>$post->title,"url"=>$post->url];
			popen("php ".APP_PATH.'/task/MailTo.php -fcommentEmail -d'.base64_encode(json_encode($Rep))."&", "r");

			/************** Insert Feed *******************/
			$feed = [];
			$feed['name'] = $Rep['name'];
			$feed['email'] = $Rep['email'];
			$feed['gravatar'] = $Rep['gravatar'];
			$feed['content'] = $Rep['name']."评论了您的文章:《".$post->title."》<a target='_blank' style='font-size:14px;color:#0083D6;' href='/Admin/CommentManage/commentView/".$cid.".html'>".mb_substr( $Rep['content'], 0, 60,"utf-8")."</a> ";
			$feed['created_at'] = date("Y-m-d H:i:s");
			Feed::add($feed);
		}
		return $this->renderJson(['code'=>200,'errmsg'=>"ok"]);
	}
}
