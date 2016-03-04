<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\usr\traits\SiteInfo;

class PostList extends Control
{
	use SiteInfo;
	private $CateModel = NULL;
	private $PostModel = NULL;
	private $request = NULL;
	private $session = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->session = Factory::make("session");
		$this->CateModel = Model::make("Category");
		$this->PostModel = Model::make("Post");
	}

	/**
	 * Post List
	 * @return void
	 */
	public function index()
	{
		$pageNum = 5;

		$where = [];

		if( !$page = intval($this->get("page")) )
		{
			$page = 1;
		}

		$skip = ($page-1)*$pageNum;
		$select = ["id","ptype","title","url","public","html","created_at"];
		$PostList = $this->PostModel->select( $select )->skip($skip)->limit($pageNum)->orderby("created_at","DESC")->get();
		$postlists = [];
		$imgReg = '/<img\s+src=[\'"].*[\'"]\s*>/i';
		foreach ($PostList as $post) 
		{
			if(preg_match($imgReg,$post->html, $mat)){$post->first_img = $mat[0];}
			$post->summary = mb_substr( strip_tags($post->html), 0, 200, "utf-8");
			$post->ptype = ucfirst($post->ptype);
			$postlists[] = $post;
		}
		if( $this->AjaxRequest )
    	{
    		return $this->renderJson(['code'=>200,"postList"=>$postlists]);
    	} else {
    		$Category = $this->CateModel->get();
			$this->assign("categories", 	$Category);
			$this->assign("postList", 		$postlists);
			$this->assign("site", 			$this->getSiteInfo());
			$this->assign("user", 			tSession::getLoginedUserInfo());
			$this->display("adminPostList.html");
		}
	}

	public function getPostByCategoryOnAjax()
	{
		if( $this->AjaxRequest )
    	{
    		if( !$categoryId = strip_tags($this->get("categoryId")) )
    		{
    			return $this->renderJson([400,"Invalid parameter input: categoryId"]);
    		}
    		$postListStr = $this->CateModel->select("postList","postNum")->where("id",$categoryId)->first();
    		$postList_array = explode(",", $postListStr->postList );
    		$pageNum = 5;
    		$PageNo = 1;
    		if( $page = intval($this->get("page")) )
    		{
    			$PageNo = $page;
    		}
    		$skip = ($PageNo-1)*$pageNum;
    		$select = ["id","ptype","title","url","public","html","created_at"];
    		$posts = $this->PostModel
    				->skip($skip)
    				->limit($pageNum)
    				->select($select)
    				->orderby("created_at","DESC")
    				->whereIn("id", $postList_array)
    				->get();

    		$postlists = [];
    		$imgReg = '/<img\s+src=[\'"].*[\'"]\s*>/i';
			foreach ($posts as $post) 
			{
				if(preg_match($imgReg,$post->html, $mat)){$post->first_img = $mat[0];}
				$post->summary = mb_substr( strip_tags($post->html), 0, 200, "utf-8");
				$post->ptype = ucfirst($post->ptype);
				$postlists[] = $post;
			}

    		return $this->renderJson(['code'=>200,'postList'=>$postlists]);

    	}else{
    		return $this->renderString("Access denied!");
    	}
	}
}