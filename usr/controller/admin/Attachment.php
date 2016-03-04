<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\usr\traits\SiteInfo;
use lOngmon\Hau\core\component\Config;

class Attachment extends Control
{
	use SiteInfo;
	public function index()
	{
		$pageNum = 24;
		if( !$page = $this->get('page') )
		{
			$page = 1;
		}

		$skip	 		= 	($page-1)*$pageNum;
		$FileModel 		= 	Model::make("file");
		$files 			= 	$FileModel->getFiles($skip,$pageNum);
		$totalFiles 	= 	$FileModel->count();
		$totalPage 		= 	ceil($totalFiles/$pageNum);
		$afiles 		= 	[];
		$filelength 	= 	count($files);

		foreach($files as $file) 
		{
			$nfile 			= 	[];
			$nfile['url'] 	= 	str_replace(ROOT_PATH, '', 
								Config::get("SAVE-UPLOAD-DIR").'/'.$file->filename);
			$nfile['id'] 	= 	$file->id;
			$afiles[] 		= 	$nfile;
		}
		if( $this->AjaxRequest )
		{
			return $this->renderJson(['code'=>200,"files"=>$afiles]);
		}else
		{
			$this->assign("totalPage", 	$totalPage);
			$this->assign("files", 		$afiles);
			$this->assign("site", 		$this->getSiteInfo());
			$this->assign("morefile",	$filelength<$totalFiles);

			return $this->display("adminArchives.html");
		}
		
	}
}