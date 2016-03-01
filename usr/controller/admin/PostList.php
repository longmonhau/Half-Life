<?php lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\usr\bundle\SlideBar;
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
	public function lists()
	{
		$page = 1;
		$pageNum = 10;

		$where = [];

		$postTotal = $this->PostModel->count();

		if( $P = $this->post("page") )
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

		if( $cate = $this->post("cate") )
		{
			$where['category'] = $cate;
		}

		$Category = $this->CateModel->get();
		if( !empty( $where ) )
		{
			$this->PostModel = $this->PostModel->where($where);
		}

		$skip = ($page-1)*$pageNum;
		$select = ["id","title","url","public"];
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
			$this->assign("tags", SlideBar::getTags());
			$this->assign("postList", $PostList);

			$this->assign("user", tSession::getLoginedUserInfo());
			$this->display("postList.html");
		}
	}

	public function getPostByCategoryOnAjax()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
    	   && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest")
    	{
    		$categoryId = $this->post("categoryId");
    		$postListStr = $this->CateModel->select("postList","postNum")->where("categoryId",$categoryId)->first();
    		$postList_array = explode(",", $postListStr->postList );

    		$pageNum = 10;
    		$PageNo = 1;
    		if( $page = $this->post("page") )
    		{
    			$PageNo = $page;
    		}
    		$skip = ($PageNo-1)*$pageNum;

    		$posts = $this->PostModel->skip($skip)->limit($pageNum)->orderby("created_at","DESC")->whereIn("id", $postList_array)->get();

    		return $this->renderJson(['code'=>200,'postList'=>iterator_to_array($posts)]);

    	}else{
    		return $this->renderString("Access denied!");
    	}
	}
}