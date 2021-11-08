<?php

use api\util\Response;
use Bramus\Router\Router;

include_once 'vendor/autoload.php';

include_once 'config/config.php';

$router = new Router();

include_once 'routes/api.php';

try {
    session_start();
    $router->run();
} catch (Throwable $e) {
    Response::getResponse()->setCode($e->getCode());
    Response::getResponse()->setError($e->getMessage(), $e->getCode());
    Response::getResponse()->setData(null);
}

Response::getResponse()->send();