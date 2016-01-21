<?php namespace lOngmon\Hau\core\template;

interface TemplateInterface
{
	public function assign( $key, $val = [] );

	public function display( $html );
}