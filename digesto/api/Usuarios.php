<?php

namespace api;

use Exception;
use helpers\Response;
use models\Usuario;

/**
 * Class Usuarios
 *
 * @package api
 */
abstract class Usuarios {

    /**
     * @throws Exception
     */
    public static function getUsuarios(): void {
        Response::getResponse()->appendData('usuarios', Usuario::getUsuarios());
        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getUsuario(int $id = 0): void {
        Response::getResponse()->appendData('usuario', Usuario::getUsuarioById($id));
        Response::getResponse()->setStatus('success');
    }

    /**
     * @throws Exception
     */
    public static function createUsuario(): void {
        throw new Exception('Not implemented', 504);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateUsuario(int $id = 0): void {
        throw new Exception('Not implemented', 504);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteUsuario(int $id = 0): void {
        throw new Exception('Not implemented', 504);
    }
}
