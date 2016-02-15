<?php namespace lOngmon\Hau\usr\controller;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\bundle\SlideBar;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\traits\AdminInfo;
use lOngmon\Hau\usr\traits\SiteInfo;

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
		if( $cid = $commentModel->insertGetId($Rep) )
		{
			$Rep['receiver'] = $this->getAdminEmail();
			$Rep["post"] = ["title"=>$post->title,"url"=>$post->url];
			popen("php ".APP_PATH.'/task/MailTo.php -fcommentEmail -d'.base64_encode(json_encode($Rep))."&", "r");

			/************** Insert Feed *******************/
			$FeedModel = Model::make("Feed");
			$feed = [];
			$feed['name'] = $Rep['name'];
			$feed['email'] = $Rep['email'];
			$feed['gravatar'] = $Rep['gravatar'];
			$feed['content'] = $Rep['name']."评论了您的文章: <br/><a target='_blank' style='font-size:14px;color:#0083D6;' href='/Admin/CommentManage/commentView/".$cid.".html'>".$post->title."<br/>\"".substr( $Rep['content'], 0, 180)."\"</a> ";
			$FeedModel->insert( $feed );
		}
		return $this->renderJson(['code'=>200,'errmsg'=>"ok"]);
	}
}