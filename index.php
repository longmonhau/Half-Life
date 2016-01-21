<?php

include "vendor/autoload.php";

use lOngmon\Hau\core\Kernel;

$app = Kernel::boot();

$app->run();