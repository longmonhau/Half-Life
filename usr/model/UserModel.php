<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class UserModel extends Model
{
    protected $table = "users";

    public function getUserById( $id )
    {
        return $this->where( "id", $id )->first();
    }

    public function getUserByName( $name )
    {
        return $this->where("name", $name)->first();
    }
}