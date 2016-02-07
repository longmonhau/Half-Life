<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\core\Model;

class Category extends Control
{
	private $request = null;

	private $CateModel = null;

	public function __construct()
	{
		parent::__construct();
		$this->request = Factory::make("request");
		$this->CateModel = Model::make("Category");
	}
	public function index()
	{
		$categories = $this->CateModel->get();
		$this->assign("categories", $categories);
		$this->display("categoryIndex.html");
	}

	public function edit()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
    		if( !$title = $this->request->get("title") )
    		{
    			return $this->renderJson(['code'=>400,'errmsg'=>"Missing requried parameter:title"]);
    		}
    		if( !$cateId = $this->request->get("cateid") )
    		{
    			return $this->renderJson(['code'=>400,'errmsg'=>"Missing required parameter: cateid"]);
    		}
    		$desc = $this->request->get("desc");
    		
    		
    		if( $thisCategoryModel = $this->CateModel->getCategoryById( $cateId ) )
    		{
    			$thisCategoryModel->title = $title;
    			$thisCategoryModel->desp = $desc;
    			$thisCategoryModel->save();
    			return $this->renderJson(['code'=>200,'errmsg'=>'ok']);
    		} else {
    			$category = [];
    			$category['title'] = $title;
    			$category['categoryId'] = $cateId;
    			$category['postNum'] = 0;
    			$category['desp'] = $desc;
    			$insertId = $this->CateModel->insert($category);
    			return $this->renderJson(['code'=>200,'errmsg'=>'ok',"id"=>$insertId]);
    		}
    		
    	} else
	    {
	    	return $this->renderString("Access denied!");	
	    }
	}

	public function del()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
    		$categoryId = $this->request->get("categoryId");
    		if( $categoryId == 'default' )
    		{
    			return $this->renderJson(['code'=>403,"errmsg"=>"Default category can not delete"]);
    		}

    		if($cateModel = $this->CateModel->getCategoryById($categoryId))
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
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
        && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
        {
            $categories = $this->CateModel->get();
            $categories = iterator_to_array( $categories );
            return $this->renderJson(['code'=>200,"categories"=>$categories]);
        } else {
            return $this->renderString("Access denied!");
        }
    }
}