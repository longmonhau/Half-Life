<?php namespace lOngmon\Hau\usr\model;

use lOngmon\Hau\core\Model;

class SiteInfoModel extends Model
{
	protected $table = "siteInfo";

	public function getSiteInfo()
	{
		return $this->get();
	}

	public function addSiteInfo( array $info )
	{
		foreach ($info as $i => $f) 
		{
			if( $setting = $this->where("meta", $i)->first() )
			{
				$setting->val = $f;
				$setting->save();
			} else{
				$this->insert(["meta"=>$i, "val"=>$f,"created_at"=>date("Y-m-d H:i:s")]);
			}
		}
	}

	public function getMeta($meta)
	{
		if( $Meta = $this->where("meta", $meta)->first() )
		{
			return $Meta;
		} else{
			$this->meta = $meta;
			return $this;
		}
	}
}