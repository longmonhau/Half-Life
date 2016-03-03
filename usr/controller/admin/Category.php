<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\bundle\tSession;

class Category extends Control
{
	private $request = null;

	private $CateModel = null;

	public function __construct()
	{
		parent::__construct();
		$this->CateModel = Model::make("Category");
	}
	public function index()
	{
		$categories = $this->CateModel->get();
		$this->assign("categories", $categories);
        $this->assign("user", tSession::getLoginedUserInfo());
		$this->display("adminCategory.html");
	}

	public function edit()
	{
		if( $this->AjaxRequest )
    	{
    		if( !$title = $this->post("title") )
    		{
    			return $this->renderJson(['code'=>400,'errmsg'=>"Missing requried parameter:title"]);
    		}
    		if( !$cateId = $this->post("cateid") )
    		{
    			return $this->renderJson(['code'=>400,'errmsg'=>"Missing required parameter: cateid"]);
    		}

    		if( $thisCategoryModel = $this->CateModel->getCategoryByCid( $cateId ) )
    		{
    			$thisCategoryModel->title = $title;
    			$thisCategoryModel->save();
    			return $this->renderJson(['code'=>200,'errmsg'=>'ok']);
    		} else {
    			$category = [];
    			$category['title'] = $title;
    			$category['categoryId'] = $cateId;
    			$category['postNum'] = 0;
				$category['created_at'] = date("Y-m-d H:i:s");
    			$insertId = $this->CateModel->insertGetId($category);
    			return $this->renderJson(['code'=>200,'errmsg'=>'ok',"id"=>$insertId]);
    		}

    	} else
	    {
	    	return $this->renderString("Access denied!");
	    }
	}

	public function del()
	{
		if( $this->AjaxRequest )
    	{
    		$categoryId = $this->post("categoryId");
    		if( $categoryId == 'default' )
    		{
    			return $this->renderJson(['code'=>403,"errmsg"=>"Default category can not delete"]);
    		}

    		if($cateModel = $this->CateModel->getCategoryByCid($categoryId))
    		{
    			$cateModel->delete();
    			return $this->renderJson(["code"=>200,"errmsg"=>"ok"]);
    		} else
    		{
    			return $this->renderJson(['code'=>401,"errmsg"=>"Category dose not exists!"]);
    		}
    	} else {
    		return $this->renderString("Access denied!");
    	}
	}

    public function loadCategoryAjax()
    {
        if( $this->AjaxRequest )
        {
            $categories = $this->CateModel->get();
            $categories = iterator_to_array( $categories );
            return $this->renderJson(['code'=>200,"categories"=>$categories]);
        } else {
            return $this->renderString("Access denied!");
        }
    }
}
