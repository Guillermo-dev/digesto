<?php

namespace api;

use Exception;
use helpers\Response;
use helpers\Request;
use models\Usuario;
use models\Permiso;

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
        if (!isset($_SESSION['user']))
            throw new Exception('Forbidden', 403);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_create', $usuarioId))
            throw new Exception('Forbidden', 403);

        $usuarioData = Request::getBodyAsJson();

        if (!isset($usuarioData->email))
            throw new Exception('datos incompletos');
        if (!isset($usuarioData->nombre))
            throw new Exception('datos incompletos');
        if (!isset($usuarioData->apellido))
            throw new Exception('datos incompletos');

        $usuario = new Usuario();
        $usuario->setEmail($usuarioData->email);
        $usuario->setNombre($usuarioData->nombre);
        $usuario->setApellido($usuarioData->apellido);

        Usuario::createUsuario($usuario);

        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateUsuario(): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Forbidden', 403);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_update', $usuarioId))
            throw new Exception('Forbidden', 403);

        $usuarioData = Request::getBodyAsJson();

        if (!isset($usuarioData->usuarioId))
            throw new Exception('datos incompletos');
        if (!isset($usuarioData->email))
            throw new Exception('datos incompletos');
        if (!isset($usuarioData->nombre))
            throw new Exception('datos incompletos');
        if (!isset($usuarioData->apellido))
            throw new Exception('datos incompletos');

        $usuario = new Usuario();
        $usuario->setId($usuarioData->usuarioId);
        $usuario->setEmail($usuarioData->email);
        $usuario->setNombre($usuarioData->nombre);
        $usuario->setApellido($usuarioData->apellido);

        Usuario::updateUsuario($usuario);

        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteUsuario(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Forbidden', 403);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_delete', $usuarioId))
            throw new Exception('Forbidden', 403);

        Usuario::deleteUsuario($id);

        Response::getResponse()->setStatus('success');
    }
}
