<?php

use Bramus\Router\Router;

include_once 'vendor/autoload.php';

$router = new Router();

include_once 'routes/api.php';

try {
    $router->run();
} catch (Throwable $e) {
    echo $e;
}