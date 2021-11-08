<?php

namespace api;

use Exception;
use models\Permiso;
use api\util\Response;

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
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getPermiso(int $id = 0): void {
        Response::getResponse()->appendData('permiso', Permiso::getPermisoById($id));
    }
}
