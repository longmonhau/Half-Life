<?php namespace lOngmon\Hau\usr\controller;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\bundle\SlideBar;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\usr\traits\SiteInfo;

class Index extends Control
{
	use SiteInfo;
	public function __construct()
	{
		parent::__construct();
		$this->assign("category", SlideBar::getCategoryAll());
		$this->assign("tags", SlideBar::getTags());
		$this->assign("Site",$this->getSiteInfo());
	}

	public function index()
	{
		$posts = $this->fetchPosts(1,10);
		$this->assign("posts", $posts);
		if( $loginedUser = tSession::getLoginedUserInfo() )
		{
			$this->assign("adminlogined", true);
			$this->assign("loginedUserName", $loginedUser->sname);
		}
		
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

	/**
	 * 按分类显示文章
	 * @param  string $category
	 * @return void
	 */
	public function categoryPost($category)
	{
		$CateModel = Model::make("Category");
		$cate = $CateModel->getCategoryByCid( $category );
		$post_array = explode(",", $cate->postList);

		$pageNum = 10;
		$totalPage = ceil($cate->postNum/$pageNum);
		$request = Factory::make("session");
		if( !$page = $request->get("page") )
		{
			$page = 1;
		}
		if( $page > $totalPage )
		{
			$page = $totalPage;
		}
		if( $page <= 0 )
		{
			$page = 1;
		}
		$skip = ($page-1)*$pageNum;

		$PostModel = Model::make("Post");
		$posts = $PostModel->whereIn("id", $post_array)->skip($skip)->orderby("created_at","DESC")->limit($pageNum)->get();
		$this->assign("posts", $posts);
		$this->display("index.html");
	}

	public function searchPostBy($key)
	{
		$postModel = Model::make("Post");
		$posts = $postModel->getPostsByKeyword($key);
		$this->assign("posts", $posts);
		$this->display("index.html");
	}

	public function tagPost( $tag )
	{
		$postModel = Model::make("Post");
		$posts = $postModel->getTagPosts($tag);
		$this->assign("posts", $posts);
		$this->display("index.html");
	}
}