<?php

namespace api;

use Exception;
use helpers\Response;
use models\Permiso;

abstract class Permisos {

  public static function getPermisos(): void {
    Response::getResponse()->appendData('permisos', Permiso::getPermisos());
    Response::getResponse()->setStatus('success');
  }

  public static function getPermiso(int $id = 0): void {
    Response::getResponse()->appendData('permiso', Permiso::getPermisoById($id));
    Response::getResponse()->setStatus('success');
  }


  public static function createPermiso(): void {
    throw new Exception('Not implemented', 504);
  }

  public static function updatePermiso(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }

  public static function deletePermiso(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }
}
