<?php namespace lOngmon\Hau\usr\controller;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;

class Index extends Control
{
	public function index()
	{
		$posts = $this->fetchPosts(1,10);
		$this->assign("posts", [1,2,3,4]);
		$this->display("index.html");
	}

	private function fetchPosts($page, $pagelength )
	{
		$PModel = Model::make("Post");
		if( $page == 1 )
		{
			$posts = [];
			$posts[] = $PModel->where("top",1)->first();
			$normalP = $PModel->where("top",0)->limit($pagelength-1)->orderby("created_at", 'DESC')->get();
			array_push($posts, $normalP);
			return $posts;
		}
		return $PModel->where("top",0)->skip(($page-1)*$pagelength)->limit($pagelength)->orderby()->get();
	}
}