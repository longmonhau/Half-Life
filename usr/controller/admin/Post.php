<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\usr\bundle\SlideBar;

class Post extends Control
{
	private $CateModel = NULL;
	private $PostModel = NULL;
	private $request = NULL;
	private $session = NULL;

	public function __construct()
	{
		parent::__construct();
		$this->request = Factory::make("request");
		$this->session = Factory::make("session");
		$this->CateModel = Model::make("Category");
		$this->PostModel = Model::make("Post");
	}

	/**
	 * Post List
	 * @return void
	 */
	public function lists()
	{
		$page = 1;
		$pageNum = 1;
		
		$where = [];

		$postTotal = $this->PostModel->count();
		
		if( $P = $this->request->get("page") )
		{
			if( $P > ceil($postTotal/$pageNum) )
			{
				$page = ceil($postTotal/$pageNum);
			} else if( $P <= 0 )
			{
				$page = 1;
			} else{
				$page = $P;
			}
		}

		if( $cate = $this->request->get("cate") )
		{
			$where['category'] = $cate;
		}

		$Category = $this->CateModel->get();
		if( !empty( $where ) )
		{
			$this->PostModel = $this->PostModel->where($where);
		}

		$skip = ($page-1)*$pageNum;
		$select = ["id","title","url"];
		$PostList = $this->PostModel->select( $select )->skip($skip)->limit($pageNum)->orderby("created_at","DESC")->get();


		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
    	   && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest")
    	{
    		return $this->renderJson(['code'=>200,"postList"=>$PostList]);
    	} else {
			$this->assign("Category", $Category);
			$pageTotal = ceil($postTotal/$pageNum);
			if( $pageTotal > 1 )
			{
				$pageNav = range(1, $pageTotal);
				$this->assign("pageNav", $pageNav);
			}
			$this->assign("_login_cookie_key",$this->session->get("_login_cookie_key"));
			$this->assign("tags", SlideBar::getTags());
			$this->assign("postList", $PostList);
			$this->display("postList.html");
		}
	}

	/**
	 * Edit a new Post
	 * @return void
	 */
	public function edit()
	{
		if( $postId = $this->request->query->get("pid") )
		{
			$Post = $this->PostModel->getPostById($postId);
			$this->assign("Post", $Post);
		}

		$Category = $this->CateModel->get();
		$this->assign("Category", $Category);
		$this->assign("_login_cookie_key",$this->session->get("_login_cookie_key"));
		$this->display("postEdit.html");
	}

	/**
	 * Post a post in post method
	 * @return print json format string
	 */
	public function submit()
	{
		$post = [];
		$postId = $this->request->get("postId");

		if( !$post['title'] = $this->request->get("title") )
		{
			$post['title'] = "Untitled";
		}

		if( !$post['url'] = $this->request->get("url") )
		{
			$post['url'] = date("YmdHis");
		}
		
		if( !$post['created_at'] = $this->request->get("created_at") )
		{
			$post['created_at'] = date("Y-m-d H:i:s");
		}

		if( !$post['category'] = $this->request->get("cateVal") )
		{
			$post['category'] = 1;
		}

		$post['markdown'] = $this->request->get("markdown");
		$post['html'] = $this->request->get("htmlContent");

		if( !$length = stripos($post['html'], '<!--more-->') )
		{
			$length = 600;
		}

		$post['summary'] = substr(strip_tags($post['html']), 0, $length);
		if( preg_match('/<img\s*src=[\'"]+([^\'"]+)[\'"]+.*>/', $post['html'], $mat) )
		{
			$post['summary'] = "<img src='".$mat[1]."'/>".$post['summary'];
		}

		$post['tags'] = $this->request->get('tags');

		/**
		 * edit an old post or public a new one!
		 * @var [type]
		 */
		
		/**
		 * if post have already exist
		 * @var [type]
		 */
		if( $postId && $thisPost = $this->PostModel->getPostById($postId) )
		{
			$thisPost->title = $post['title'];
			$thisPost->url = $post['url'];
			$thisPost->summary = $post['summary'];
			$thisPost->category = $post['category'];
			$thisPost->markdown = $post['markdown'];
			$thisPost->html = $post['html'];
			$thisPost->tags = $post['tags'];
			$thisPost->created_at = $post['created_at'];

			$thisPost->save();
			$oldCateList = explode(',', $thisPost->category);
			$cateList = explode(',', $post['category']);
			$shouldDeleteCateList = array_diff($oldCateList, $cateList);
			foreach ($shouldDeleteCateList as $ce) 
			{
				$cateModel = $this->CateModel->getCategoryById($ce);
				$cateModel->deletePost($postId);
			}
			$newCateList = array_diff($cateList, $oldCateList);
			foreach ($cateList as $cate) 
			{
				$cateModel = $this->CateModel->getCategoryById($cate);
				$cateModel->addPost($postId);
			}
		}
		/**
		 * or just a new post public
		 */
		else{
			$insertId = $this->PostModel->insertGetId($post);
			$cateList = explode(',', $post['category']);
			foreach ($cateList as $cate) 
			{
				$cateModel = $this->CateModel->getCategoryById($cate);
				$cateModel->addPost( $insertId );
			}
		}

		/**
		 * rending the json string to client
		 */
		return $this->renderJson(['code'=>200,'errmsg'=>"ok","go_url"=>"/Admin/Post/List.html"]);
	}

	public function del()
	{
		if( !$postId = $this->request->get("postId") )
		{
			return $this->renderJson(["code"=>400,"errmsg"=>"Missing requried parameter:postId"]);
		}
		
		$postIdList = explode(",", $postId);
		$CommentModel = Model::make("Comment");
		foreach ($postIdList as $pid) 
		{
			if( $Post = $this->PostModel->getPostById( $pid ) )
			{
				$Post->delPost();
			}
		}
		$CommentModel->destroy( $postIdList );
		
		return $this->renderJson(['code'=>200,'errmsg'=>"ok"]);
	}
}