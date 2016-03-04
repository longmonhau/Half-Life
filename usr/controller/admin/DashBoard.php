<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\component\DateTime;
use lOngmon\Hau\usr\bundle\tSession;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\core\component\Config;

class DashBoard extends Control
{
	use SiteInfo;
	public function index()
	{
		$PostModel 			= 	Model::make("Post");
		$CategoryModel 		= 	Model::make("Category");
		$CommentModel 		= 	Model::make("Comment");
		$MessageModel 		= 	Model::make("Message");
		$AttachmentModel    = 	Model::make("file");

		$postNum 			= 	$PostModel->where("public", 1)->count();
		$draftNum 			= 	$PostModel->where("public",0)->count();
		$CategoryNum 		= 	$CategoryModel->count();
		$CommentNum 		= 	$CommentModel->count();
		$newMessage 		= 	$MessageModel->where("resp",0)->orderBy("created_at","DESC")->get();
		$attachment			= 	$AttachmentModel->count();

		$this->assign("totalCategoryNum", 	$CategoryNum );
		$this->assign("totalPostNum", 		$postNum );
		$this->assign("site", 				$this->getSiteInfo());
		$this->assign("draftNum", 			$draftNum);
		$this->assign("newMsgCount",		count($newMessage) );
		$this->assign("files", 				$this->getRecentImages());
		$this->assign("messages", 			iterator_to_array($newMessage) );
		$this->assign("AttachmentCount",	$attachment);
		$this->assign("user", tSession::getLoginedUserInfo());

		$this->display( "adminIndex.html" );
	}

	private function getRecentImages()
	{
		$FileModel 	= Model::make("file");
		$files 		= $FileModel->getFiles(0,6);
		$ret_files 	= [];

		foreach ($files as $file) 
		{
			$ret_files[]['url'] = str_replace(ROOT_PATH,"",Config::get("SAVE-UPLOAD-DIR")."/".$file->filename);
		}
		return $ret_files;
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
		$feeds = $FeedModel->getFeeds(10);
		$feedCount = $FeedModel->count();
		$feeds = iterator_to_array( $feeds );
		foreach ($feeds as $k=>$feed)
		{
			$feeds[$k]['humanLookTime'] = DateTime::humanLook( $feed->created_at );
		}
		return ["FeedCount"=>$feedCount,"feeds"=>$feeds];
	}
}
