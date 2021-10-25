<?php

namespace api;

use Exception;
use helpers\Response;
use models\Usuario;

abstract class Usuarios {

  public static function getUsuarios(): void {
    Response::getResponse()->appendData('usuarios', Usuario::getUsuarios());
    Response::getResponse()->setStatus('success');
  }

  public static function getUsuario(int $id = 0): void {
    Response::getResponse()->appendData('usuario', Usuario::getUsuarioById($id));
    Response::getResponse()->setStatus('success');
  }


  public static function createUsuario(): void {
    throw new Exception('Not implemented', 504);
  }

  public static function updateUsuario(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }

  public static function deleteUsuario(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }
}
