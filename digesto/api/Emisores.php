<?php

namespace api;

use Exception;
use helpers\Response;
use models\Emisor;

abstract class Emisores {

  public static function getEmisores(): void {
    Response::getResponse()->appendData('emisores', Emisor::getEmisores());
    Response::getResponse()->setStatus('success');
  }

  public static function getEmisor(int $id = 0): void {
    Response::getResponse()->appendData('emisor', Emisor::getEmisorById($id));
    Response::getResponse()->setStatus('success');
  }


  public static function createEmisor(): void {
    throw new Exception('Not implemented', 504);
  }

  public static function updateEmisor(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }

  public static function deleteEmisor(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }
}
