<?php

namespace api;

use Exception;
use JsonException;
use models\Connection;
use models\exceptions\ModalException;
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
     * @throws ModalException
     * @throws ApiException
     */
    public static function getUsuarios(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_access', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        Response::getResponse()->appendData('usuarios', Usuario::getUsuarios());
    }

    /**
     * @param int $id
     *
     * @throws ModalException
     * @throws ApiException
     */
    public static function getUsuario(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        Response::getResponse()->appendData('usuario', Usuario::getUsuarioById($id));
    }

    /**
     * @throws ModalException
     * @throws ApiException
     * @throws JsonException
     */
    public static function createUsuario(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_create', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $usuarioData = Request::getBodyAsJson();

        $usuario = new Usuario();

        if (isset($usuarioData->email))
            $usuario->setEmail($usuarioData->email);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        Usuario::createUsuario($usuario);
    }

    /**
     * @param int $id
     *
     * @throws ModalException
     * @throws ApiException
     * @throws JsonException
     */
    public static function updateUsuario(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_update', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $usuarioData = Request::getBodyAsJson();

        $usuario = Usuario::getUsuarioById($id);
        if (!$usuario)
            throw new ApiException('El usuario no existe', Response::NOT_FOUND);

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
     * @throws ModalException
     * @throws ApiException
     */
    public static function deleteUsuario(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_delete', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $usuario = Usuario::getUsuarioById($id);
        if (!$usuario)
            throw new ApiException('El usuario no existe', Response::NOT_FOUND);

        Usuario::deleteUsuario($usuario->getId());
    }

    /**
     * @param int $usuarioId
     *
     * @throws ApiException
     * @throws ModalException
     */
    public static function getPermisos(int $usuarioId = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuario = Usuario::getUsuarioById($usuarioId);
        if (!$usuario)
            throw new ApiException('El usuario no existe', Response::NOT_FOUND);

        Response::getResponse()->appendData('permisosActivos', Permiso::getPermisosByUsuario($usuario));
        Response::getResponse()->appendData('permisos', Permiso::getPermisos());
    }

    /**
     * @param int $usuarioId
     *
     * @throws ApiException
     * @throws JsonException
     * @throws ModalException
     */
    public static function updatePermisos(int $usuarioId = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $loggedUserId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('usuarios_update', $loggedUserId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $requestData = Request::getBodyAsJson();

        $usuario = Usuario::getUsuarioById($usuarioId);
        if (!$usuario)
            throw new ApiException('El usuario no existe', Response::NOT_FOUND);

        Connection::begin();

        if (isset($requestData->assign)) {
            if (!is_array($requestData->assign))
                throw new ApiException('Bad Request', Response::BAD_REQUEST);
            if (count($requestData->assign) > 0)
                Permiso::assignPermisoToUsuario($usuario, $requestData->assign);
        }

        if (isset($requestData->remove)) {
            if (!is_array($requestData->remove))
                throw new ApiException('Bad Request', Response::BAD_REQUEST);
            if (count($requestData->remove) > 0)
                Permiso::removePermisoFromUsuario($usuario, $requestData->remove);
        }

        Connection::commit();
    }
}
