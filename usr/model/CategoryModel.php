<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class CategoryModel extends Model
{
	protected $table = "category";

	/**
	 * get category by primary key
	 * @param  int $id
	 * @return Model
	 */
	public function getCategoryById($id)
	{
		return $this->find($id);
	}

	/**
	 * get category by category id
	 * @param  string $cid
	 * @return Model
	 */
	public function getCategoryByCid( $cid )
	{
		return $this->where("categoryId", $cid)->first();
	}

	/**
	 * 删除分类中的文件列表，对文章数量减1
	 * @param  int $postId 文章id
	 * @return void
	 */
	public function deletePost( $postId )
	{
		$this->postNum = $this->postNum>0?$this->postNum-1:0;
		$postList = explode(",",$this->postList);
		$this->postList = join(",",array_diff($postList, [$postId]));
		$this->save();
	}

	/**
	 * 增加分类中的文件列表，对文章数量加1
	 * @param int $postId 文章id
	 */
	public function addPost($postId)
	{
		$this->postNum++;
		$postList = $this->postList;
		$postList .= ",".$postId;
		$this->postList = ltrim($postList,",");
		$this->save();
	}
}