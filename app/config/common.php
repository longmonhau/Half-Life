<?php

return [
	"URL-SUFFIX" => [".html",".htm",".xhtml"],
	
	"LOG-PATH"    => APP_PATH.'/Log/'.date("Y-m-d"),

	//========== template setting ===========
	"TEMPLATE-ENGINE" => "twig",

	"TEMPLATE-DIR" => APP_PATH.'/theme',

	"TEMPLATE-CACHE-DIR" => APP_PATH."/theme/compiled~",

	"TEMPLATE-THEME" => "simple",

	"TEMPLATE-CACHE" => false,

	"TEMPLATE-DEBUG" => false,

	"TEMPLATE-CHARSET" => "utf-8",

	"TEMPLATE-AUTOESCAPE" => false,

	"TEMPLATE-OPTIMIZATION" => -1,

	"TEMPLATE_SUFFIX" => ".html"
];