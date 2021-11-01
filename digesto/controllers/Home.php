<?php

namespace controllers;

/**
 * Class Home
 *
 * @package controllers
 */
abstract class Home {
    /**
     *
     */
    public static function index(): void {
        echo file_get_contents('src/pages/home/home.html');
    }
    public static function documento(): void {
        echo file_get_contents('src/pages/document/document.html');
    }
}