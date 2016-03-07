<?php namespace lOngmon\Hau\core\template;

use lOngmon\Hau\core\component\Config;
use lOngmon\Hau\core\Factory;
use lOngmon\Hau\core\component\Log;

class SimpleTemplate
{
	private $engine = NULL;

	private $_vars = [];

	public function __Construct()
	{
		$engine = Config::get("TEMPLATE-ENGINE");
		$this->engine = Factory::make($engine);
	}

	public function assign( $key, $val = [] )
	{
		$this->engine->assign($key,$val);
	}

	public function display( $html )
	{
		return $this->engine->display($html);
	}

	public function makeHtml( $html, $fileName )
	{
		$staticHtmlDir = Config::get("STATIC-HTML-DIR");
		if( !is_dir( $staticHtmlDir ) )
		{
			$parentDir = dirname($staticHtmlDir);
			if( !is_writable( $parentDir ) )
			{
				Log::addWarning( $parentDir."目录不可写！");
				return;
			}
			mkdir($staticHtmlDir);
			chmod($staticHtmlDir, "0777");
		} else if( !is_writable($staticHtmlDir) )
		{
			Log::addWarning("$staticHtmlDir 目录不可写");
		}
		file_put_contents($staticHtmlDir.'/'.$fileName.".html", $html);
	}
}