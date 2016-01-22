<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class PostModel extends Model
{
	protected $table = "posts";

	public function getPostsByKeyword( $key )
	{
		//
	}

	public function getPostsById( $id )
	{
		return $this->find($id);
	}
}