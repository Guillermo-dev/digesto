<?php

namespace api;

use Exception;
use helpers\Response;
use models\Pdf;

abstract class Pdfs {

  public static function getPdfs(): void {
    Response::getResponse()->appendData('pdfs', Pdf::getPdfs());
    Response::getResponse()->setStatus('success');
  }

  public static function getPdf(int $id = 0): void {
    Response::getResponse()->appendData('pdf', Pdf::getPdfById($id));
    Response::getResponse()->setStatus('success');
  }


  public static function createPdf(): void {
    throw new Exception('Not implemented', 504);
  }

  public static function updatePdf(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }

  public static function deletePdf(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }
}
