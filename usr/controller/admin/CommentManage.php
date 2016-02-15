<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;

class CommentManage extends Control
{
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
		$comment = $this->commentModel->getCommentById($cid);
		$this->assign("post", $comment->Post());
		return $this->display("commentView.html");
	}
}