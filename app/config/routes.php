<?php

return [
	"index" => ["GET","/", ["Index","index"]],

	['GET',"/Blog/{slug}",["Blog","view"]],

	["POST","/Blog/Comment",["Blog","comment"]],

	["POST","/Blog/postCommenct",["Blog","commentPost",['CommentTimeLimit']]],

	['GET',"/Category/{category}", ["Index","categoryPost"]],

	["GET","/Search/{key}", ["Index","searchPostBy"]],

	["GET","/Tags/{tag}", ["Index","tagPost"]],

	"sign" => ["GET","/admin/entrance", ["admin\User", "sign"]],

	'login'=> ['POST','/admin/login.submit',["admin\User","login"]],

	"logout" => ["GET","/User/Logout",["admin\User","logout"]],

	"dashBoard" => ['GET',"/Admin/dashBoard",["admin\DashBoard","index",["auth"]]],

	"postList" => ['GET',"/Admin/Post/List",["admin\Post","lists",["auth"]]],

	"loadmorePost" => ["POST","/Admin/Post/More",["admin\Post","lists",["auth"]]],

	"postEdit" => ['GET',"/Admin/Post/Edit",["admin\Post","edit",["auth"]]],

	"postDel" => ["POST","/Admin/Post/Del",["admin\Post","del",["auth"]]],

	"adminCategory" => ["GET","/Admin/Category",["admin\Category","index",["auth"]]],

	"adminEditCategory" => ["POST","/Admin/Category/Edit",["admin\Category","edit",['auth']]],

	"adminDelCategory" => ["POST","/Admin/Category/Del",["admin\Category","del",["auth"]]],

	"getCategoryByAjax" => ["POST","/AjaxLoadCategory",["admin\Category","loadCategoryAjax"]],

	"adminPostSubmit" => ["POST","/Post/submit",["admin\Post","submit",["auth"]]],

	"getCategoryPost" => ["POST","/getCategoryPost",["admin\Post","getPostByCategoryOnAjax"]],

	"Contactme" => ["GET","/Contact_me",["Message","index"]],

	"msgSunmit" => ["POST","/Message/Submit",["Message","submit",["CommentTimeLimit"]]],

	"ajaxFeedLoad" => ["GET","/Feed/AjaxLoad",["admin\Feed","ajaxLoadFeed",["auth"]]],

	"ajaxLoadDraft" => ["GET","/Admin/Post/ajaxLoadDraft",["admin\Post","ajaxLoadDraft",["auth"]]],

	"adminFeeds" => ["GET","/Admin/Feeds",["admin\Feed","index",["auth"]]],

	"adminMessageView" => ["GET","/Admin/Message/View/{mid:\d+}",["admin\Message","view",["auth"]]],

	"AdminMessageReply" => ["POST","/Admin/Message/Reply", ["admin\Message","reply",["auth"]]],

	"Admin/Message/Del" => ["POST","/Admin/Message/Del",["admin\Message","del",["auth"]]],

	"Admin/Comment/Manage" => ["GET","/Admin/CommentManage/commentView/{postId:\d+}",["admin\CommentManage","viewComment",["auth"]]],

	"adminCommentDel" => ["POST","/Admin/Comment/Del",["admin\CommentManage","del",["auth"]]],

	"admin/Comment/Resp" => ["POST","/admin/Comment/Resp",["admin\CommentManage","resp",["auth"]]],

	"admin/Message/List" => ["GET","/Admin/Message/List",["admin\Message","index",["auth"]]],

	"admin/Message/updateStatus" => ["POST","/admin/Message/updateStatus",["admin\Message","updateStatus",["auth"]]],

	"adminSetting" => ["GET", "/admin/Setting", ["admin\Setting", "index", ["auth"]]],

	"admin/Setting/submit" => ["POST","/admin/Setting/submit",["admin\Setting","submit",["auth"]]],

	"admin/Profile" => ["GET","/admin/Profile",["admin\Profile","index",["auth"]]],

	"/admin/Profile/submit" => ["POST","/admin/Profile/submit",["admin\Profile","submit",["auth"]]],

	"admin/Profile/chpwd" => ["POST","/admin/Profile/chpwd",["admin\Profile","chpwd",["auth"]]]
];
