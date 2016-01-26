<?php

return [
	"index" => ["GET","/", ["Index","index"]],

	['GET',"/blog/{slug}",["Blog","view"]],

	"sign" => ["GET","/admin/entrance", ["admin\User", "sign"]],

	'login'=> ['POST','/admin/login.submit',["admin\User","login"]],

	"dashBoard" => ['GET',"/Admin/dashBoard",["admin\DashBoard","index",["auth"]]]
];