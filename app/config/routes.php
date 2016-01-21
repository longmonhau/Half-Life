<?php

return [
	"index" => ["GET","/", ["Index","index"]],

	"blog" => ['GET',"/blog/{slug}",["Blog","view",["halo"]]]
];