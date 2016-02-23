<?php namespace lOngmon\Hau\core\bundle;

use lOngmon\Hau\core\Model;

class Feed
{
	public static function add( $bag )
	{
		$FeedModel = Model::make("Feed");
		$FeedModel->insert( $bag );
	}
}