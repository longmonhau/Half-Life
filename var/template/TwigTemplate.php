<?php namespace lOngmon\Hau\core\template;

use lOngmon\Hau\core\template\TemplateInterface;
use lOngmon\Hau\core\component\Config;

class TwigTemplate implements TemplateInterface
{
	private $twig = NULL;

	private $_vars = [];

	public static function newInstance()
	{
		return new static();
	}

	public function __Construct()
	{
		$template_dir = Config::get( "TEMPLATE-DIR" );

		$loader = new \Twig_Loader_Filesystem([$template_dir.'/'.Config::get("TEMPLATE-THEME"), $template_dir.'/common']);

		$option = [];
		if ( Config::get("TEMPLATE-CACHE") ) {
			$option['cache'] = Config::get( 'TEMPLATE-CACHE_DIR' );
		}
		$option['debug'] = Config::get('TEMPLATE-DEBUG');
		$option['charset'] = Config::get('TEMPLATE-CHARSET');
		$option['autoescape'] = Config::get("TEMPLATE-AUTOESCAPE");
		$option['optimizations'] = Config::get('TEMPLATE-OPTIMIZATION');

		$this->twig = new \Twig_Environment( $loader, $option );
		$this->twig->addFilter($this->summary());
		$this->twig->addFilter($this->md5());
		$this->twig->addFilter($this->explode());
	}

	public function display( $html )
	{
		return $this->twig->render( $html, $this->_vars );
	}

	public function assign( $key, $val = [] )
	{	if( is_array($key) )
		{
			$this->_vars = $key;
			return;
		}
		$this->_vars[$key] = $val;
	}

	private function summary()
	{
		$filter = new \Twig_SimpleFilter("summary", function( $content, $sp ){
			$end = stripos( $content, $sp );
			if( $end )
			{
				return substr( $content, 0, $end );
			}
			return $content;
		});
		return $filter;
	}

	private function md5()
	{
		$filter = new \Twig_SimpleFilter("md5", function( $content ){
			return md5( $content );
		});
		return $filter;
	}

	private function explode()
	{
		$filter = new \Twig_SimpleFilter("explode", function( $str, $delim ){
			return explode($delim, $str);
		});
		return $filter;
	}
}