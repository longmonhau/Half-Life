<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class SiteInfoModel extends Model
{
	protected $table = "siteInfo";

	public function getSiteInfo()
	{
		return $this->get();
	}
}