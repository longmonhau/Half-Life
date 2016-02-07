<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class FeedModel extends Model
{
	protected $table = "feed";

	public function getFeeds( $limit )
	{
		return $this->limit($limit)->orderBy("created_at", "DESC")->get();
	}
}