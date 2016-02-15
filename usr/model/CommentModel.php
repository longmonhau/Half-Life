<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\model\PostModel;

class CommentModel extends Model
{
	protected $table = "comments";

	public function getCommentsBypostId( $pid )
	{
		return $this->where("postId", $pid)->orderBy("created_at","DESC")->get();
	}

	public function getCommentById($id)
	{
		return $this->find($id);
	}

	public function Post()
	{
		return $this->belongsto("\lOngmon\Hau\usr\model\PostModel","postId");
	}
}