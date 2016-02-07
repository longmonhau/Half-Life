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
		return $this->SiteInfo = $info_combined;
	}
}