<?php

return [
	"URL-SUFFIX" 		=> 	[".html",".htm",".xhtml"],
	
	"LOG-PATH"    		=> 	APP_PATH.'/log/'.date("Y-m-d"),//请确保目录可写

	"SAVE-UPLOAD-DIR" 	=> 	STATIC_PATH.'/uploads',//请确保目录可写

	"STATIC-HTML-DIR"	=>	ROOT_PATH.'/Blog',//请确保目录可读写，并在WEB目录下

	"ALLOW-UPLOAD-TYPE" => 	["image/png",'image/gif',"image/jpeg","image/jpg","image/bmp"],

	"MAX-UPLOAD-SIZE" 	=> 	'10M',

	//========== template setting ===========
	"TEMPLATE-ENGINE" 		=> "twig",

	"TEMPLATE-DIR" 			=> APP_PATH.'/theme',

	"TEMPLATE-CACHE-DIR" 	=> APP_PATH."/theme/compiled~",

	"TEMPLATE-THEME" 		=> "black",

	"TEMPLATE-CACHE" 		=> false,

	"TEMPLATE-DEBUG" 		=> false,

	"TEMPLATE-CHARSET" 		=> "utf-8",

	"TEMPLATE-AUTOESCAPE" 	=> false,

	"TEMPLATE-OPTIMIZATION" => -1,

	"TEMPLATE_SUFFIX" 		=> ".html",

	//================== Email config ===============
	"MAIL-SERVER-CONFIG" =>[
		"HOST" 	=> 'smtp.mxhichina.com',
		"USERNAME" 	=> "root@longmonhau.com",
		"PASSWORD" 	=> "123456",
		"PORT"		=> 25,
		"FROM"		=> "root@longmonhau.com"
	]
];