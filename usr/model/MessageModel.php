<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class MessageModel extends Model
{
	protected $table = "message";

	public function getMessageById( $id )
	{
		return $this->find($id);
	}

	public function updateStatus(array $msg)
	{
		$this->whereIn("id",$msg)->update(["isread"=>'y']);
	}
}
