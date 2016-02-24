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
		return $this->display("Setting.html");
	}

	public function submit()
	{
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"])
		&& strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")
    	{
    		$siteModel = Model::make("SiteInfo");
    		$site = [];
    		$site['site_name'] = strip_tags($this->post("name"));
    		$site['site_domain'] = strip_tags($this->post("domain"));
    		$site['site_copyright'] = strip_tags( $this->post("copy"));
    		$site['site_desc'] = strip_tags( $this->post("desc"));
    		$site['site_analytices'] = $this->post("analytices");
    		$siteModel->addSiteInfo($site);
    		return $this->renderJson(200,"ok");
    	}
	}
}