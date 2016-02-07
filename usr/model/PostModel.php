<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class PostModel extends Model
{
	protected $table = "posts";

	public function getPostsByKeyword( $key )
	{
		//
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
}