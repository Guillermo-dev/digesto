<?php

use helpers\Response;

/*
 * Api routes
 */
if (isset($router)) {
    $router->setBasePath('/api');

    $router->post('/auth/login', 'api\Auth@login');
    $router->delete('/auth/logout', 'api\Auth@logout');

    $router->get('/documentos', 'api\Documentos@getDocumentos');
    $router->get('/documentos/{id}', 'api\Documentos@getDocumento');
    $router->post('/documentos', 'api\Documentos@createDocumento');
    $router->put('/documentos/{id}', 'api\Documentos@updateDocumento');
    $router->delete('/documentos/{id}', 'api\Documentos@deleteDocumento');

    $router->get('/emisores', 'api\Emisores@getEmisores');
    $router->get('/emisores/{id}', 'api\Emisores@getEmisor');
    $router->post('/emisores', 'api\Emisores@createEmisor');
    $router->put('/emisores/{id}', 'api\Emisores@updateEmisor');
    $router->delete('/emisores/{id}', 'api\Emisores@deleteEmisor');

    $router->get('/pdfs', 'api\Pdfs@getPdfs');
    $router->get('/pdfs/{id}', 'api\Pdfs@getPdf');
    $router->post('/pdfs', 'api\Pdfs@createPdf');
    $router->put('/pdfs/{id}', 'api\Pdfs@updatePdf');
    $router->delete('/pdfs/{id}', 'api\Pdfs@deletePdf');

    $router->get('/permisos', 'api\Permisos@getPermisos');
    $router->get('/permisos/{id}', 'api\Permisos@getPermiso');
    $router->post('/permisos', 'api\Permisos@createPermiso');
    $router->put('/permisos/{id}', 'api\Permisos@updatePermiso');
    $router->delete('/permisos/{id}', 'api\Permisos@deletePermiso');

    $router->get('/tags', 'api\Tags@getTags');
    $router->get('/tags/{id}', 'api\Tags@getTag');
    $router->post('/tags', 'api\Tags@createTag');
    $router->put('/tags/{id}', 'api\Tags@updateTag');
    $router->delete('/tags/{id}', 'api\Tags@deleteTag');

    $router->get('/usuarios', 'api\Usuarios@getUsuarios');
    $router->get('/usuarios/{id}', 'api\Usuarios@getUsuario');
    $router->post('/usuarios', 'api\Usuarios@createUsuario');
    $router->put('/usuarios/{id}', 'api\Usuarios@updateUsuario');
    $router->delete('/usuarios/{id}', 'api\Usuarios@deleteUsuario');

    $router->set404(function () {
        Response::getResponse()->setStatus('error');
        Response::getResponse()->setError(404, 'Not found');
        Response::getResponse()->setData(null);
    });
}
