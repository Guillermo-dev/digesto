<?php

use Bramus\Router\Router;

include_once 'vendor/autoload.php';

$router = new Router();

include_once 'routes/web.php';

try {
    session_start();
    
    $router->run();
} catch (Throwable $e) {
    echo $e;
}