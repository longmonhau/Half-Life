<?php namespace lOngmon\Hau\usr\controller;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\bundle\SlideBar;

class Index extends Control
{
	public function index()
	{
		$posts = $this->fetchPosts(1,10);
		$this->assign("posts", $posts);
		$this->assign("category", SlideBar::getCategoryAll());
		$this->assign("tags", SlideBar::getTags());
		$this->display("index.html");
	}

	private function fetchPosts($page, $pagelength )
	{
		$PModel = Model::make("Post");
		$posts = [];
		if( $page == 1 )
		{
			if( $toppost = $PModel->where("top",1)->first() )
			{
				$posts[] = $toppost;
			}
			$pagelength = $pagelength-1;
			
		}
		$normalP = $PModel->where("top",0)->where("public",1)->limit($pagelength)->orderby("created_at", 'DESC')->get();
		foreach ($normalP as $k=>$post) 
		{
			$cateList = explode(",", $post->category);
			$category = [];
			foreach ($cateList as $cateid) 
			{
				$category[] = $this->getCategory($cateid);
			}
			$post->category = $category;
			$posts[] = $post;
		}
		return $posts;
	}

	/**
	 * Get category by id
	 */
	private function getCategory( $cid )
	{
		$CateModel = Model::make("Category");
		$cate = $CateModel->getCategoryById($cid);
		//var_dump( $cate->title );
		return ['title'=>$cate->title,'id'=>$cate->id,'categoryId'=>$cate->categoryId];
	}
}