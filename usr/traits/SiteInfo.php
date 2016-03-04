<?php namespace lOngmon\Hau\usr\traits;

use lOngmon\Hau\core\Model;

trait SiteInfo
{
	private $SiteInfo = NULL;

	protected function getSiteInfo()
	{
		$SiteModel = Model::make("SiteInfo");
		$info = $SiteModel->get();
		$info = iterator_to_array( $info );
		$info_combined = [];
		foreach ($info as $k => $if) 
		{
			$info_combined[$if->meta] = $if->val;
		}
		$User = Model::make("User");
		$admin = $User->find(1);
		$info_combined["admin_sname"] = $admin->sname;
		$info_combined['admin_name']  = $admin->name;
		$info_combined['admin_avatar'] = $admin->avatar;
		$info_combined['admin_email'] = $admin->email;
		$info_combined['admin_id'] = $admin->id;
		return $this->SiteInfo = $info_combined;
	}
}