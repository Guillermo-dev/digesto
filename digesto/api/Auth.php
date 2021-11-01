<?php

namespace api;

use Exception;
use helpers\Response;

/**
 * Class Auth
 *
 * @package api
 */
abstract class Auth {
    /**
     *
     * @throws Exception
     */
    public static function login(): void {
        if (isset($_SESSION['user']))
            throw new Exception('Forbidden', 403);

        //Sign in logic

        throw new Exception('Not implemented', 503);
    }

    /**
     *
     * @throws Exception
     */
    public static function logout(): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Forbidden', 403);

        session_destroy();
        Response::getResponse()->setStatus('success');
    }
}