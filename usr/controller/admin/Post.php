<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\core\component\Config;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\usr\bundle\SlideBar;
use lOngmon\Hau\usr\traits\SiteInfo;

class Post extends Control
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
	 * Edit a new Post
	 * @return void
	 */
	public function edit()
	{
		if( $postId = $this->get("pid") )
		{
			$Post = $this->PostModel->getPostById($postId);
			$this->assign("Post", $Post);
		}
		$siteInfo = $this->getSiteInfo();
		$FileModel = Model::make("file");
		$files = $FileModel->getFiles(0, 6);
		$file_return = [];
		foreach ($files as $file) 
		{
			$file_return[]['url'] = str_replace(ROOT_PATH, '', Config::get('SAVE-UPLOAD-DIR')."/".$file->filename);
		}

		$Category = $this->CateModel->get();
		$this->assign("categorys", $Category);
		$this->assign("files", $file_return);
		$this->assign("site", $siteInfo);
		$this->display("adminEdit.html");
	}

	/**
	 * Post a post in post method
	 * @return print json format string
	 */
	public function submit()
	{
		$post = [];
		$postId = $this->post("postId");

		if( !$post['title'] = $this->post("title") )
		{
			$post['title'] = "无题";
		}

		$post['url'] = "a/".md5(date("YmdHis"));

		if( !$post['created_at'] = $this->post("created_at") )
		{
			$post['created_at'] = date("Y-m-d H:i:s");
		} else
		{
			$post['created_at'] = date("Y-m-d H:i:s", strtotime($post['created_at']));
		}

		if( !$post['category'] = $this->post("cateVal") )
		{
			$post['category'] = 1;
		}

		$post['markdown'] = $this->post("markdown");
		
		include ROOT_PATH."/var/HyperDown.php";
		$HyperDown = new \HyperDown\Parser;
		$post['html'] = $HyperDown->makeHtml($post['markdown']);

		if( !$length = stripos($post['html'], "&lt;!--more--&gt;") )
		{
			$length = 200;
		}

		$post['tags'] = $this->post('tags');

		$post['public'] = $this->post("public");
		if( $post['public'] !== '0' )
		{
			$post['public'] = 1;
		}

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
			$oldCategory = $thisPost->category;
			$thisPost->title = $post['title'];
			$thisPost->public = $post['public'];
			$thisPost->category = $post['category'];
			$thisPost->markdown = $post['markdown'];
			$thisPost->html = $post['html'];
			$thisPost->tags = $post['tags'];
			$thisPost->created_at = $post['created_at'];
			$thisPost->save();

			$oldCateList = explode(',', $oldCategory);
			$cateList = explode(',', $post['category']);
			$shouldDeleteCateList = array_diff($oldCateList, $cateList);
			foreach ($shouldDeleteCateList as $ce)
			{
				$cateModel = $this->CateModel->getCategoryById($ce);
				$cateModel->deletePost($postId);
			}

			$newCateList = array_diff($cateList, $oldCateList);
			foreach ($newCateList as $cate)
			{
				$cateModel = $this->CateModel->getCategoryById($cate);
				$cateModel->addPost($postId);
			}
		}
		/**
		 * or just a new post public
		 */
		else{
			$post['created_at'] = date("Y-m-d H:i:s");
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
		if( !$postId = $this->post("postId") )
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

	public function ajaxLoadDraft()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
    	   && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest")
    	{
    		$Drafts = $this->PostModel->where("public",0)->orderby("created_at","DESC")->get();
    		return $this->renderJson(["code"=>200,"drafts"=>iterator_to_array( $Drafts )]);
    	} else
    	{
    		return $this->renderJson(403,"access denied");
    	}
	}
}
