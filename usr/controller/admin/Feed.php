<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\component\DateTime;
use lOngmon\Hau\usr\bundle\tSession;

class Feed extends Control
{
	public function index()
	{
		$FeedModel = Model::make("Feed");
		$feeds = $FeedModel->getFeeds(20);
		$feeds = iterator_to_array( $feeds );
		foreach ($feeds as $k=>$feed) 
		{
			$feeds[$k]['humanLookTime'] = DateTime::humanLook( $feed->created_at );
		}
		$this->assign("feeds", $feeds);
		$this->assign("user", tSession::getLoginedUserInfo());
		return $this->display("feeds.html");
	}

	public function ajaxLoadFeed()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
    		$FeedModel = Model::make("Feed");
			$feeds = $FeedModel->getFeeds(5);
			$feeds = iterator_to_array( $feeds );
			foreach ($feeds as $k=>$feed) 
			{
				$feeds[$k]['humanLookTime'] = DateTime::humanLook( $feed->created_at );
			}
			return $this->renderJson(['code'=>200,'feeds'=>$feeds]);
    	} else
    	{
    		return $this->renderJson(403,"Access denied!");
    	}
	}
}