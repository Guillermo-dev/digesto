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
        echo file_get_contents('src/pages/not-found/not-found.html');
    }
}