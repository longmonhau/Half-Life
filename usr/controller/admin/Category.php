<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\core\component\Config;

class Category extends Control
{
    use SiteInfo;

	private $request = null;

	private $CateModel = null;

	public function __construct()
	{
		parent::__construct();
		$this->CateModel = Model::make("Category");
	}
	public function index()
	{
        $PostModel          =   Model::make("Post");
        $CategoryModel      =   Model::make("Category");
        $CommentModel       =   Model::make("Comment");
        $MessageModel       =   Model::make("Message");
        $AttachmentModel    =   Model::make("file");

        $postNum            =   $PostModel->where("public", 1)->count();
        $draftNum           =   $PostModel->where("public",0)->count();
        $CategoryNum        =   $CategoryModel->count();
        $CommentNum         =   $CommentModel->count();
        $newMessage         =   $MessageModel->where("resp",0)->orderBy("created_at","DESC")->get();
        $attachment         =   $AttachmentModel->count();

        $this->assign("totalCategoryNum",   $CategoryNum );
        $this->assign("totalPostNum",       $postNum );
        $this->assign("draftNum",           $draftNum);
        $this->assign("newMsgCount",        count($newMessage) );
        $this->assign("files",              $this->getRecentImages());
        $this->assign("AttachmentCount",    $attachment);

		$categories = $this->CateModel->get();
		$this->assign("categories", $categories);
        $this->assign("user", tSession::getLoginedUserInfo());
        $this->assign("site",$this->getSiteInfo());
		$this->display("adminCategory.html");
	}
    private function getRecentImages()
    {
        $FileModel  = Model::make("file");
        $files      = $FileModel->getFiles(0,6);
        $ret_files  = [];

        foreach ($files as $file) 
        {
            $ret_files[]['url'] = str_replace(ROOT_PATH,"",Config::get("SAVE-UPLOAD-DIR")."/".$file->filename);
        }
        return $ret_files;
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
