<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class CategoryModel extends Model
{
	protected $table = "category";

	public function getCategoryById($id)
	{
		return $this->find($id);
	}

	public function deletePost( $postId )
	{
		$this->postNum = $this->postNum>0?$this->postNum-1:0;
		$postList = explode(",",$this->postList);
		$this->postList = join(",",array_diff($postList, [$postId]));
		$this->save();
	}

	public function addPost($postId)
	{
		$this->postNum++;
		$postList = $this->postList;
		$postList .= ",".$postId;
		$this->postList = ltrim($postList,",");
		$this->save();
	}
}