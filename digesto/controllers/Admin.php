<?php

namespace controllers;

/**
 * Class Admin
 *
 * @package controllers
 */
abstract class Admin {
    /**
     *
     */
    private static function middleware(): void {
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            exit;
        }
    }

    /**
     *
     */
    public static function home(): void {
        self::middleware();
        echo file_get_contents('src/pages/admin-home/admin-home.html');
    }

    /**
     *
     */
    public static function usuarios(): void {
        self::middleware();
        echo file_get_contents('src/pages/admin-usuarios/admin-usuarios.html');
    }

    /**
     *
     */
    public static function documento(): void {
        self::middleware();
        echo file_get_contents('src/pages/admin-document/admin-document.html');
    }

    /**
     *
     */
    public static function newDocumento(): void {
        self::middleware();
        echo file_get_contents('src/pages/admin-new-documento/admin-new-documento.html');
    }
}