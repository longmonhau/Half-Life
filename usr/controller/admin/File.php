<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\component\Upload;

use lOngmon\Hau\core\Control;

class File extends Control
{
	public function upload($name)
	{
		$upload = Upload::newInstance($name);
		$res = $upload->go();
		return $this->renderJson($res);
	}
}