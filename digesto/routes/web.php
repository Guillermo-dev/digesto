<?php

/*
 * Web routes
 */
if (isset($router)) {
    $router->get('/', 'controllers\Home@index');
    $router->get('/documentos/{id}', 'controllers\Home@documento');

    $router->get('/auth/login', 'controllers\Auth@login');
    $router->get('/auth/logout', 'controllers\Auth@logout');

    $router->get('/admin/', 'controllers\Admin@home');
    $router->get('/admin/usuarios', 'controllers\Admin@usuarios');
    $router->get('/admin/documento/{id}', 'controllers\Admin@documento');
    $router->get('/admin/documento/', 'controllers\Admin@newDocumento');

    $router->set404('controllers\NotFound@index');
}
