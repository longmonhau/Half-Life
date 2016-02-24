<?php namespace lOngmon\Hau\core\component;

use lOngmon\Hau\core\component\Config;

class Upload
{
	private $upload_dir = '';

	private $allow_type = [];

	private $max_size = '5M';

	private $storage = NULL;

	private $file = NULL;

	public static function newInstance( $name )
	{
		$self = new self();
		$self->upload_dir = Config::get("SAVE-UPLOAD-DIR");
		$self->allow_type = Config::get('ALLOW-UPLOAD-TYPE');
		$self->max_size = Config::get("MAX-UPLOAD-SIZE");

		$self->storage = new \Upload\Storage\FileSystem($self->upload_dir);
		$self->file = new \Upload\File($name, $self->storage);
		$newFileName = \uniqid();
		$self->file->setName($newFileName);
		$self->file->addValidations(
			new \Upload\Validation\Mimetype($self->allow_type),
			new \Upload\Validation\Size($self->max_size)
		);

		return $self;
	}

	public function go()
	{
		try{
			$this->file->upload();
			return ["success"=>1,"message"=>"ok","url"=>'/static/uploads/'.$this->file->getNameWithExtension()];
		} 
		catch( \Exception $e )
		{
			return ["success"=>0,"message"=>$e->getMessage()];
		}
	}
}