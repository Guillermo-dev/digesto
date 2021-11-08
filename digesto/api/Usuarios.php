<?php

namespace api;

use Exception;
use models\Usuario;
use models\Permiso;
use api\util\Request;
use api\util\Response;
use api\exceptions\ApiException;

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
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getUsuario(int $id = 0): void {
        Response::getResponse()->appendData('usuario', Usuario::getUsuarioById($id));
    }

    /**
     * @throws Exception
     */
    public static function createUsuario(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_create', $usuarioId))
            throw new ApiException('Forbidden', Response::RESPONSE_FORBIDDEN);

        $usuarioData = Request::getBodyAsJson();

        $usuario = new Usuario();

        if (isset($usuarioData->email))
            $usuario->setEmail($usuarioData->email);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        if (isset($usuarioData->nombre))
            $usuario->setNombre($usuarioData->nombre);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        if (isset($usuarioData->apellido))
            $usuario->setApellido($usuarioData->apellido);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        Usuario::createUsuario($usuario);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateUsuario(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_update', $usuarioId))
            throw new ApiException('Forbidden', Response::RESPONSE_FORBIDDEN);

        $usuarioData = Request::getBodyAsJson();

        $usuario = Usuario::getUsuarioById($id);
        if (!$usuario)
            throw new ApiException('El usuario no existe', Response::RESPONSE_NOT_FOUND);

        if (isset($usuarioData->email))
            $usuario->setEmail($usuarioData->email);

        if (isset($usuarioData->nombre))
            $usuario->setNombre($usuarioData->nombre);

        if (isset($usuarioData->apellido))
            $usuario->setApellido($usuarioData->apellido);

        Usuario::updateUsuario($usuario);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteUsuario(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_delete', $usuarioId))
            throw new ApiException('Forbidden', Response::RESPONSE_FORBIDDEN);

        $usuario = Usuario::getUsuarioById($id);
        if (!$usuario)
            throw new ApiException('El usuario no existe', Response::RESPONSE_NOT_FOUND);

        Usuario::deleteUsuario($usuario->getId());
    }
}
