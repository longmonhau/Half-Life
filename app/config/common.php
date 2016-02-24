<?php

return [
	"URL-SUFFIX" 	=> [".html",".htm",".xhtml"],
	
	"LOG-PATH"    	=> APP_PATH.'/log/'.date("Y-m-d"),

	"SAVE-UPLOAD-DIR" => STATIC_PATH.'/uploads',

	"ALLOW-UPLOAD-TYPE" => ["image/png",'image/gif',"image/jpeg","image/jpg","image/bmp"],

	"MAX-UPLOAD-SIZE" => '5M',

	//========== template setting ===========
	"TEMPLATE-ENGINE" 		=> "twig",

	"TEMPLATE-DIR" 			=> APP_PATH.'/theme',

	"TEMPLATE-CACHE-DIR" 	=> APP_PATH."/theme/compiled~",

	"TEMPLATE-THEME" 		=> "blue",

	"TEMPLATE-CACHE" 		=> false,

	"TEMPLATE-DEBUG" 		=> false,

	"TEMPLATE-CHARSET" 		=> "utf-8",

	"TEMPLATE-AUTOESCAPE" 	=> false,

	"TEMPLATE-OPTIMIZATION" => -1,

	"TEMPLATE_SUFFIX" 		=> ".html",

	//================== Email config ===============
	"MAIL-SERVER-CONFIG" =>[
		"HOST" 	=> 'smtp.mxhichina.com',
		"USERNAME" 	=> "root@userextra.com",
		"PASSWORD" 	=> "Kiss963.",
		"PORT"		=> 25,
		"FROM"		=> "root@userextra.com"
	]
];