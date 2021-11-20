<?php

namespace api;

use Exception;
use models\Emisor;
use models\Permiso;
use api\util\Request;
use api\util\Response;
use api\exceptions\ApiException;

/**
 * Class Emisores
 *
 * @package api
 */
abstract class Emisores {

    /**
     * @throws Exception
     */
    public static function getEmisores(): void {
        Response::getResponse()->appendData('emisores', Emisor::getEmisores());
    }

    /**
     * @throws Exception
     */
    public static function getEmisor(int $id = 0): void {
        Response::getResponse()->appendData('emisor', Emisor::getEmisorById($id));
    }

    /**
     * @throws Exception
     */
    public static function createEmisor(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Debe iniciar sesión para realizar esta acción', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('emisores_create', $usuarioId))
            throw new ApiException('No tiene permisos necesarios', Response::FORBIDDEN);

        $emisorData = Request::getBodyAsJson();
        if (!isset($emisorData->nombre))
            throw new ApiException('Error interno', Response::BAD_REQUEST);

        $emisor = new Emisor();

        if (isset($emisorData->nombre))
            $emisor->setNombre($emisorData->nombre);
        else throw new ApiException('Nombre del emisor requerido', Response::BAD_REQUEST);

        Emisor::createEmisor($emisor);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateEmisor(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Debe iniciar sesión para realizar esta acción', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('emisores_update', $usuarioId))
            throw new ApiException('No tiene permisos necesarios', Response::FORBIDDEN);

        $emisorData = Request::getBodyAsJson();

        $emisor = Emisor::getEmisorById($id);
        if (!$emisor)
            throw new ApiException('El emisor no existe', Response::NOT_FOUND);

        if (isset($emisorData->nombre))
            $emisor->setNombre($emisorData->nombre);
        else throw new ApiException('Nombre del emisor requerido', Response::BAD_REQUEST);

        Emisor::updateEmisor($emisor);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteEmisor(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Debe iniciar sesión para realizar esta acción', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('emisores_delete', $usuarioId))
            throw new ApiException('No tiene permisos necesarios', Response::FORBIDDEN);

        $emisor = Emisor::getEmisorById($id);
        if (!$emisor)
            throw new ApiException('El emisor no existe', Response::NOT_FOUND);

        Emisor::deleteEmisor($emisor->getId());
    }
}
