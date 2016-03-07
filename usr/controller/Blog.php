<?php namespace lOngmon\Hau\usr\controller;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\bundle\SlideBar;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\usr\traits\AdminInfo;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\core\component\Config;

class Blog extends Control
{
	use AdminInfo;
	use SiteInfo;
	public function view($url)
	{
		$PostModel 		= 	Model::make('Post');
		$CategoryModel 	= 	Model::make("Category");
		$UserModel 		= 	Model::make("User");
		$user 			= 	$UserModel->getAdmin();

		$Post 			= 	$PostModel->where("url", Config::get("POST-STATIC-PATH").$url)->first();
		$time 			= 	strtotime($Post->created_at);
		$Post->postTime = 	date("F d, Y", $time);

		$cate_arr = [];
		if( stripos($Post->category, ",") === false )
		{
			$cate_arr[] = $Post->category;
		} else
		{
			$cate_arr = explode(",", $Post->category);
		}
		$aCategory = [];
		foreach ($cate_arr as $cate) 
		{

			$aCategory[] = $CategoryModel->getCategoryById($cate);
		}
		$Post->aCategory = $aCategory;
		$Post->author = $user->sname;
		$this->assign("post", $Post);
		$this->assign("site",$this->getSiteInfo());
		
		$this->toHtml("blogView.html", $url);
		//$this->display("blogView.html");
	}

	public function comment()
	{
		if( !$postId = intval($this->post("postId")) )
		{
			return $this->renderJson(400, "Invalid input post id!");
		}
		$commentModel = Model::make("Comment");
		$comments = $commentModel->where("resp",0)->where("postId", $postId)->get();
		foreach ($comments as $k => $comment)
		{
			$comments[$k]["subcomment"] = iterator_to_array($comment->getSubComments( $comment->id ));
		}
		return $this->renderJson(["code"=>200,"comments"=> iterator_to_array($comments)]);
	}

	
}
