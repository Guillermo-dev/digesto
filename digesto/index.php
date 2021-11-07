<?php

use Bramus\Router\Router;

include_once 'vendor/autoload.php';

include_once 'config/config.php';

$router = new Router();

include_once 'routes/web.php';

try {
    session_start();
    $router->run();
} catch (Throwable $e) {
    \controllers\Error::page500();
}