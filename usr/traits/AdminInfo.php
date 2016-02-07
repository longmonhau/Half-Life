<?php namespace lOngmon\Hau\usr\traits;

use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\Factory;

trait AdminInfo
{
	private $admin = NULL;

	public function getAdmin()
	{
		$userModel = Model::make("User");
		$this->admin = $userModel->getAdmin();
	}

	public function getAdminEmail()
	{
		if( !$this->admin )
		{
			$this->getAdmin();
		}
		return $this->admin->email;
	}

	public function getAdminSname()
	{
		if( !$this->admin )
		{
			$this->getAdmin();
		}
		return $this->admin->sname;	
	}


	public function getAdminName()
	{
		if( !$this->admin )
		{
			$this->getAdmin();
		}
		return $this->admin->name;	
	}
}