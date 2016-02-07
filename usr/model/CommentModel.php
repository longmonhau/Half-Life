<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class CommentModel extends Model
{
	protected $table = "comments";

	public function getCommentsBypostId( $pid )
	{
		return $this->where("postId", $pid)->orderBy("created_at","DESC")->get();
	}
}