<?php

return [
	"index" => ["GET","/", ["Index","index"]],

	"blog" => ['GET',"/blog/{slug}",["Blog","view"]],

	"sign" => ["GET","/admin/entrance", ["User", "signin"]]
];