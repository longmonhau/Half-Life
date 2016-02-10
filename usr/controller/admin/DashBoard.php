<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\component\DateTime;

class DashBoard extends Control
{
	public function index()
	{
		$PostModel 		= 	Model::make("Post");
		$CategoryModel 	= 	Model::make("Category");
		$CommentModel 	= 	Model::make("Comment");
		
		$postNum = $PostModel->count();
		$CategoryNum = $CategoryModel->count();
		$CommentNum = $CommentModel->count();
		$Feed = $this->getFeeds();

		$this->assign("totalCategoryNum", $CategoryNum );
		$this->assign("totalPostNum", $postNum );
		$this->assign("totalCommentNum", $CommentNum );
		$this->assign("draftList", $this->getDraft() );
		$this->assign("feeds", $Feed['feeds']);
		$this->assign("feedCount", $Feed['FeedCount']);
		$this->display( "dashBoard.html" );
	}

	private function getDraft()
	{
		$PostModel = Model::make("Post");
		$draft = $PostModel->where("public",0)->get();
		return iterator_to_array( $draft );
	}

	private function getFeeds()
	{
		$FeedModel = Model::make("Feed");
		$feeds = $FeedModel->getFeeds(5);
		$feedCount = $FeedModel->count();
		$feeds = iterator_to_array( $feeds );
		foreach ($feeds as $k=>$feed) 
		{
			$feeds[$k]['humanLookTime'] = DateTime::humanLook( $feed->created_at );
		}
		return ["FeedCount"=>$feedCount,"feeds"=>$feeds];
	}
}