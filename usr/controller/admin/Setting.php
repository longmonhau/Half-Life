<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\usr\bundle\tSession;

class Setting extends Control
{
	use SiteInfo;
	public function index()
	{
		$this->assign("site", $this->getSiteInfo());
		$this->assign("user", tSession::getLoginedUserInfo());
		return $this->display("adminSettings.html");
	}

	public function submit()
	{
		if( $this->AjaxRequest )
    	{
    		$siteModel = Model::make("SiteInfo");
    		$site = [];
    		foreach ($this->post as $name=>$val) 
    		{
    			$site[$name] = $val;
    		}
    		$siteModel->addSiteInfo($site);
    		return $this->renderJson(200,"ok");
    	}
	}
}