<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class PostModel extends Model
{
	protected $table = "posts";

	public function comments()
	{
		return $this->hasMany("\lOngmon\Hau\usr\model\CommentModel","postId");
	}
	
	public function getPostsByKeyword( $key )
	{
		$titleSelect = $this->where("title","like","%{$key}%")->where("public",1);
		$posts = $this->where("tags","like","%{$key}%")->where("public",1)->union($titleSelect)->get();
		return $posts;
	}

	public function getPostById( $id )
	{
		return $this->find($id);
	}

	public function delPost()
	{
		$id = $this->id;
		$categid = $this->category;

		$this->delete();

		$cateid_array = explode(",", $categid);
		$CateModel = Model::make("Category");
		foreach ($cateid_array as $k => $cid)
		{
			$Category = $CateModel->getCategoryById( $cid );
			$Category->deletePost( $id );
		}
	}

	public function getTagPosts( $tag )
	{
		$posts = $this->where("tags","like","%{$tag}%")->where("public",1)->get();
		return $posts;
	}
}
