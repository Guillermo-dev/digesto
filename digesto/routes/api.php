<?php

use api\util\Response;

/*
 * Api routes
 */
if (isset($router)) {
    $router->setBasePath('/api');

    $router->get('', function () {
        Response::getResponse()->appendData('message', 'Welcome!');
    });

    $router->post('/auth/login', 'api\Auth@login');
    $router->delete('/auth/logout', 'api\Auth@logout');

    $router->get('/documentos', 'api\Documentos@getDocumentos');
    $router->get('/documentos/(\d+)', 'api\Documentos@getDocumento');
    $router->post('/documentos', 'api\Documentos@createDocumento');
    $router->post('/documentos/(\d+)', 'api\Documentos@updateDocumento');
    $router->delete('/documentos/(\d+)', 'api\Documentos@deleteDocumento');

    $router->get('/emisores', 'api\Emisores@getEmisores');
    $router->get('/emisores/(\d+)', 'api\Emisores@getEmisor');
    $router->post('/emisores', 'api\Emisores@createEmisor');
    $router->put('/emisores/(\d+)', 'api\Emisores@updateEmisor');
    $router->delete('/emisores/(\d+)', 'api\Emisores@deleteEmisor');

    $router->get('/tags', 'api\Tags@getTags');
    $router->get('/tags/(\d+)', 'api\Tags@getTag');
    $router->post('/tags', 'api\Tags@createTag');
    $router->put('/tags/(\d+)', 'api\Tags@updateTag');
    $router->delete('/tags/(\d+)', 'api\Tags@deleteTag');

    $router->get('/usuarios', 'api\Usuarios@getUsuarios');
    $router->post('/usuarios', 'api\Usuarios@createUsuario');
    $router->get('/usuarios/(\d+)', 'api\Usuarios@getUsuario');
    $router->put('/usuarios/(\d+)', 'api\Usuarios@updateUsuario');
    $router->delete('/usuarios/(\d+)', 'api\Usuarios@deleteUsuario');

    $router->get('/usuarios/(\d+)/permisos', 'api\Usuarios@getPermisos');
    $router->put('/usuarios/(\d+)/permisos', 'api\Usuarios@updatePermisos');

    $router->set404(function () {
        Response::getResponse()->setCode(Response::NOT_FOUND);
        Response::getResponse()->setError('The end point does not exist', Response::NOT_FOUND);
        Response::getResponse()->setData(null);
    });
}
