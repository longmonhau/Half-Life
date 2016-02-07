<?php namespace lOngmon\Hau\usr\bundle;

use lOngmon\Hau\core\Model;

class SlideBar
{
	/**
	 * get all category
	 */
	public static function getCategoryAll()
	{
		$CateModel = Model::make("Category");
		return iterator_to_array( $CateModel->get() );
	}

	/**
	 * get all tags
	 */
	public static function getTags()
	{
		$PostModel = Model::make("Post");
		$tags = $PostModel->select("tags")->get();
		$tags = iterator_to_array( $tags );
		$tagsInOne = [];
		foreach ($tags as $tag) 
		{
			if( $tag->tags == '' ) continue;
			$tagsInOne = array_merge($tagsInOne, explode('|',$tag->tags));
		}
		return $tagsInOne;
	}
}