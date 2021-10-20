<?php

/*
 * Web routes
 */
if (isset($router)) {
    $router->get('/', 'controllers\Home@index');

    $router->set404('controllers\NotFound@index');
}