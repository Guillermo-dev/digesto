<?php

namespace controllers;

/**
 * Class NotFound
 *
 * @package controllers
 */
abstract class NotFound {
    /**
     *
     */
    public static function index(): void {
        header('HTTP/1.0 404 Not Found');
        echo file_get_contents('src/pages/not-found/not-found.html');
    }
}