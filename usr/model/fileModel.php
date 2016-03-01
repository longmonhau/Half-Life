<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class fileModel extends Model
{
	protected $table = "file";

	public function getFiles($skip, $num)
	{
		return $this->skip($skip)->limit($num)->orderby("created_at", "DESC")->get();
	}
}