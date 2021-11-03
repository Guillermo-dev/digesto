<?php

namespace api;

use Exception;
use helpers\Response;
use helpers\Request;
use models\Emisor;
use models\Permiso;

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
        Response::getResponse()->setStatus('success');
    }

    /**
     * @throws Exception
     */
    public static function getEmisor(int $id = 0): void {
        Response::getResponse()->appendData('emisor', Emisor::getEmisorById($id));
        Response::getResponse()->setStatus('success');
    }


    /**
     * @throws Exception
     */
    public static function createEmisor(): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Forbidden', 403);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('emisores_create', $usuarioId))
            throw new Exception('Forbidden', 403);

        $emisorData = Request::getBodyAsJson();
        if (!isset($emisorData->nombre))
            throw new Exception('datos incompletos');

        $emisor = new Emisor();
        $emisor->setNombre($emisor->nombre);

        Emisor::createEmisor($emisor);

        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateEmisor(): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Forbidden', 403);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('emisores_update', $usuarioId))
            throw new Exception('Forbidden', 403);

        $emisorData = Request::getBodyAsJson();
        if (!isset($emisorData->idEmisor))
            throw new Exception('datos incompletos');
        if (!isset($emisorData->nombre))
            throw new Exception('datos incompletos');

        $emisor = new Emisor();
        $emisor->setId($emisorData->emisorId);
        $emisor->setNombre($emisor->nombre);

        Emisor::updateEmisor($emisor);

        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteEmisor(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Forbidden', 403);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('emisores_delete', $usuarioId))
            throw new Exception('Forbidden', 403);

        Emisor::deleteEmisor($id);

        Response::getResponse()->setStatus('success');
    }
}
