<?php

return [
	"index" => ["GET","/", ["Index","index"]],

	['GET',"/Blog/{slug}",["Blog","view"]],

	["POST","/Blog/Comment",["Blog","comment"]],

	["POST","/Blog/postCommenct",["Blog","commentPost",['CommentTimeLimit']]],

	"sign" => ["GET","/admin/entrance", ["admin\User", "sign"]],

	'login'=> ['POST','/admin/login.submit',["admin\User","login"]],

	"dashBoard" => ['GET',"/Admin/dashBoard",["admin\DashBoard","index",["auth"]]],

	"postList" => ['GET',"/Admin/Post/List",["admin\Post","lists",["auth"]]],

	"loadmorePost" => ["POST","/Admin/Post/More",["admin\Post","lists",["auth"]]],

	"postEdit" => ['GET',"/Admin/Post/Edit",["admin\Post","edit",["auth"]]],

	"postDel" => ["POST","/Admin/Post/Del",["admin\Post","del",["auth"]]],

	"adminCategory" => ["GET","/Admin/Category",["admin\Category","index",["auth"]]],

	"adminEditCategory" => ["POST","/Admin/Category/Edit",["admin\Category","edit",['auth']]],

	"adminDelCategory" => ["POST","/Admin/Category/Del",["admin\Category","del",["auth"]]],

	"getCategoryByAjax" => ["POST","/AjaxLoadCategory",["admin\Category","loadCategoryAjax"]],

	"adminPostSubmit" => ["POST","/Post/submit",["admin\Post","submit",["auth"]]]
];