<?php

use Bramus\Router\Router;
use helpers\Response;

include_once 'vendor/autoload.php';

$router = new Router();

include_once 'routes/api.php';

try {
    session_start();
    
    $router->run();

} catch (Throwable $e) {
    Response::getResponse()->setStatus('error');
    Response::getResponse()->setError($e->getCode(), $e->getMessage());
    Response::getResponse()->setData(null);
}

header('Content-Type: application/json; charset=UTF-8');
echo json_encode(Response::getResponse());