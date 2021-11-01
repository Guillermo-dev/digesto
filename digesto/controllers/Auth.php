<?php

namespace controllers;

/**
 * Class Auth
 *
 * @package controllers
 */
abstract class Auth {
    /**
     *
     */
    public static function login(): void {
        if (isset($_SESSION['user'])) {
            header('Location: /admin/');
            return;
        }

        echo file_get_contents('src/pages/login/login.html');
    }

    /**
     *
     */
    public static function logout(): void {
        if (!isset($_SESSION['user'])) {
            header('Location: /auth/login');
            return;
        }

        session_destroy();
        header('Location: /');
    }
}