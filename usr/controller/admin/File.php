<?php namespace lOngmon\Hau\usr\controller\admin;

use lOngmon\Hau\core\component\Upload;

use lOngmon\Hau\core\Control;
use lOngmon\Hau\core\Model;
use lOngmon\Hau\core\component\Config;

class File extends Control
{
	public function upload($name)
	{
		$upload = Upload::newInstance($name);
		$file = $upload->fileInfo();
		$res = $upload->go();
		if( $res['success'] == 1 )
		{
			$fileModel = Model::make("file");
			$file['created_at'] = date("Y-m-d H:i:s");
			$fileModel->insert($file);
		}
		
		return $this->renderJson($res);
	}

	public function listUploads()
	{
		if( $this->AjaxRequest ){
			if( !$page = intval($this->get->page) )
			{
				$page = 1;
			}
			$pageNum = 18;
			$skip = ($page-1)*$pageNum;
			$fileModel = Model::make("file");
			$files = $fileModel->getFiles($skip, $pageNum );
			$files = iterator_to_array( $files );
			$file_return = [];
			foreach ($files as $file ) 
			{
				$file_return[]['file_url'] = str_replace(ROOT_PATH,"",Config::get('SAVE-UPLOAD-DIR').'/'.$file['filename']);	
			}
			if( count($file_return ) > 0 )
			{
				return $this->renderJson(['code'=>200,'files'=>$file_return]);
			} else{
				return $this->renderJson(401,"No files exists");
			}
		} else
		{
			return $this->renderString("Access denied");
		}
	}

	public function deleteFiles()
	{
		if( $this->AjaxRequest )
		{
			if( !$fileIds = $this->post("fileIds") )
			{
				return $this->renderJson(400,"Missing requried parameter: fileIds");
			}
			$fileArray = explode( ",", $fileIds );
			$FileModel = Model::make("file");
			foreach ($fileArray as $fileId) 
			{
				$file 			= 	$FileModel->find($fileId);
				$fileWithPath 	= 	Config::get('SAVE-UPLOAD-DIR').'/'.$file->filename;
				if( is_file($fileWithPath) )
				{
					unlink($fileWithPath);
				}
				$file->delete();
			}
			return $this->renderJson(200,"ok");
		} else
		{
			return $this->renderString("Access denied");
		}
	}
}