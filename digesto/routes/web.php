<?php

/*
 * Web routes
 */
if (isset($router)) {
    $router->get('/', 'controllers\Home@index');
    $router->get('/documentos/{id}', 'controllers\Home@documento');

    $router->set404('controllers\NotFound@index');
}