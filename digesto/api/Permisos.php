<?php

namespace api;

use Exception;
use helpers\Response;
use models\Permiso;

/**
 * Class Permisos
 *
 * @package api
 */
abstract class Permisos {

    /**
     * @throws Exception
     */
    public static function getPermisos(): void {
        Response::getResponse()->appendData('permisos', Permiso::getPermisos());
        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getPermiso(int $id = 0): void {
        Response::getResponse()->appendData('permiso', Permiso::getPermisoById($id));
        Response::getResponse()->setStatus('success');
    }
}
