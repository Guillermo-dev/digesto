<?php

namespace api;

use Exception;
use models\Tipo;
use models\Permiso;
use api\util\Request;
use api\util\Response;
use api\exceptions\ApiException;

/**
 * Class Tipos
 *
 * @package api
 */
abstract class Tipos {

    /**
     * @throws Exception
     */
    public static function getTipos(): void {
        Response::getResponse()->appendData('tipos', Tipo::getTipos());
    }

    /**
     * @throws Exception
     */
    public static function getTipo(int $id = 0): void {
        Response::getResponse()->appendData('tipo', Tipo::getTipoById($id));
    }

    /**
     * @throws Exception
     */
    public static function createTipo(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Debe iniciar sesión para realizar esta acción', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_create', $usuarioId))
            throw new ApiException('No tiene permisos necesarios', Response::FORBIDDEN);

        $tipoData = Request::getBodyAsJson();

        if (!isset($tipoData->nombre))
            throw new ApiException('Error interno', Response::BAD_REQUEST);

        $tipo = new Tipo();

        if (isset($tipoData->nombre))
            $tipo->setNombre($tipoData->nombre);
        else throw new ApiException('Nombre del emisor requerido', Response::BAD_REQUEST);

        Tipo::createTipo($tipo);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateTipo(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Debe iniciar sesión para realizar esta acción', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_update', $usuarioId))
            throw new ApiException('No tiene permisos necesarios', Response::FORBIDDEN);

        $tipoData = Request::getBodyAsJson();

        $tipo = Tipo::getTipoById($id);
        if (!$tipo)
            throw new ApiException('El tipo no existe', Response::NOT_FOUND);

        if (isset($tipoData->nombre))
            $tipo->setNombre($tipoData->nombre);
        else throw new ApiException('Nombre del tipo requerido', Response::BAD_REQUEST);

        Tipo::updateTipo($tipo);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteTipo(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Debe iniciar sesión para realizar esta acción', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_delete', $usuarioId))
            throw new ApiException('No tiene permisos necesarios', Response::FORBIDDEN);

        $tipo = Tipo::getTipoById($id);
        if (!$tipo)
            throw new ApiException('El tipo no existe', Response::NOT_FOUND);

        Tipo::deleteTipo($tipo->getId());
    }
}
