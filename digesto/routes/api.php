<?php

/*
 * Api routes
 */
if (isset($router)) {
    $router->setBasePath('/api');

    $router->get('/documentos', 'api\Documentos@getDocumentos');
    $router->get('/documentos/{id}', 'api\Documentos@getDocumento');
    $router->post('/documentos', 'api\Documentos@createDocumento');
    $router->put('/documentos/{id}', 'api\Documentos@updateDocumento');
    $router->delete('/documentos/{id}', 'api\Documentos@deleteDocumento');

    $router->set404('api\NotFound@index');
}