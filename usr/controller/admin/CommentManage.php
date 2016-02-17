<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\core\component\Mailer;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\core\component\Log;

class CommentManage extends Control
{
	use SiteInfo;
	private $postModel = NULL;
	private $commentModel = NULL;

	public function __construct()
	{
		parent::__Construct();
		$this->postModel = Model::make("Post");
		$this->commentModel = Model::make("Comment");
	}

	public function viewComment( $cid )
	{
		if( !$theComment = $this->commentModel->getCommentById( $cid ) )
		{
			$this->assign("post", null);
			return $this->display("commentView.html");
		}
		$post = $theComment->post;
		$category_arr = [];
		$postcate = explode(",",$post->category);
		$CategoryModel = Model::make("Category");
		foreach ($postcate as $cate )
		{
			$category_arr[] = $CategoryModel->getCategoryById($cate);
		}
		$comRepl = $this->commentModel->where("resp", $cid)->get();
		$comments = $post->comments()->where("id", "!=", $cid)->where("resp",0)->get();
		$this->assign("post", $post );
		$this->assign("postCategory", $category_arr );
		$this->assign("thecomment", $theComment );
		$this->assign("comRepls", iterator_to_array($comRepl));
		$this->assign("comments", $comments );
		return $this->display("commentView.html");
	}

	public function del()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
			$request = Factory::make("request");
			$cid = intval($request->get("cid"));
			if( $cid < 1 )
			{
				return $this->renderJson(400, "Invalid input parameter!");
			}
			if( !$comment = $this->commentModel->getCommentById( $cid ) )
			{
				return $this->renderJson(501,"评论不存在！");
			}
			$comment->delete();
			$this->commentModel->where("resp", $cid)->delete();
			return $this->renderJson(200,"ok");
		} else {
			return $this->renderString("Access denied!");
		}
	}

	public function resp()
	{
		$request = Factory::make("request");
		if( !$cid = intval($request->get("cid")) )
		{
			return $this->renderJson(400,"Invalid input parameter:cid");
		}
		if( !$content = strip_tags( $request->get("resContent") ) )
		{
			return $this->renderJson(400,"Invalid input response content!");
		}
		$comment = $this->commentModel->getCommentById($cid);
		$post = $comment->post;
		$user = tSession::getLoginedUserInfo();
		$resp = [];
		$resp['resp'] = $cid;
		$resp['postId'] = $post->id;
		$resp['name'] = $user->name;
		$resp['email'] = $user->email;
		$resp['gravatar'] = $user->avatar;
		$resp['content'] = $content;
		$resp['created_at'] = date("Y-m-d H:i:s");
		$this->commentModel->insert( $resp );

		/**
		 * Send an email to original commentor;
		 */
		$site = $this->getSiteInfo();
		$mail = Mailer::newInstance();
		$mail->addAddress($comment->email, $comment->name);
		$mail->addReplyTo($user->email, $user->name);
		$mail->isHTML(TRUE);
		$mail->Subject = "来自{$user->name} 的回复！";
		$mail->Body = "{$content}";
		$mail->Body .= "<hr/>原文地址：<a href='http://{$site['site_domain']}/Blog/{$post->url}.html' target='_blank'>".$post->title."</a><br/>";
		$mail->Body .= "您在{$comment->created_at}发表的评论：<br/>".$comment->content;
		if( ! $mail->send() )
		{
			Log::error( $mail->ErrorInfo );
		}
		return $this->renderJson(200,"ok");
	}
}
