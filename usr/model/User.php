<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class User extends Model
{
	protected $table = "users";

	public function getUserById( $id )
	{
		return $this->where( "id", $id )->first();
	}
}