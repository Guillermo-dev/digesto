<?php

namespace models;

use Exception;
use JsonSerializable;

class Pdf implements JsonSerializable {

  private $id;

  private $contenido;

  private $path;

  public function __construct(int $id = 0, string $contenido = '', string $path = '') {
    $this->id = $id;
    $this->contenido = $contenido;
    $this->path = $path;
  }

  public function getId(): int {
    return $this->id;
  }

  public function getContenido(): int {
    return $this->contenido;
  }

  public function getPath(): int {
    return $this->path;
  }

  public function setId(int $id): Pdf {
    $this->id = $id;
    return $this;
  }

  public function setContenido(string $contenido): Pdf {
    $this->contenido = $contenido;
    return $this;
  }

  public function setPath(string $path): Pdf {
    $this->path = $path;
    return $this;
  }

  public function jsonSerialize(): array {
    return get_object_vars($this);
  }


  /********************************************************/

  public static function getPdfs(): array {
    $conn = Connection::getConnection();

    $query = 'SELECT pdf_id, contenido, path, FROM pdfs';
    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_last_error($conn));

    $pdfs = [];
    while (($row = pg_fetch_assoc($rs)) != false) {
      $pdf = new Pdf();
      $pdf->setId($row['pdf_id']);
      $pdf->setContenido($row['contenido']);
      $pdf->setPath($row['path']);
      $pdfs[] = $pdf;
    }

    if (($error = pg_last_error($conn)) != false)
      throw new Exception($error);

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));

    return $pdfs;
  }

  public static function getPdfById(int $id): ?Pdf {
    $conn = Connection::getConnection();

    $query = sprintf('SELECT pdf_id, contenido, path FROM pdfs WHERE pdf_id=%d', $id);
    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_last_error($conn));

    $pdf = null;
    if (($row = pg_fetch_assoc($rs)) != false) {
      $pdf = new Pdf();
      $pdf->setId($row['permiso_id']);
      $pdf->setContenido($row['contenido']);
      $pdf->setPath($row['path']);
    }

    if (($error = pg_last_error($conn)) != false)
      throw new Exception($error);

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error());

    return $pdf;
  }

  public static function createPdf(Pdf $pdf): void {
    $conn = Connection::getConnection();

    $query = sprintf(
      "INSERT INTO pdfs (contenido, path) VALUES ('%s','%s') RETURNING Currval('pdfs_pdf_id_seq')",
      pg_escape_string($pdf->getContenido()),
      pg_escape_string($pdf->getPath())
    );

    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_last_error($conn));

    if (($row = pg_fetch_row($rs)))
      $pdf->setId(($row[0]));
    else throw new Exception(pg_last_error($rs));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }

  public static function updatePdf(Pdf $pdf): void {
    $conn = Connection::getConnection();

    $query = sprintf(
      "UPDATE pdfs SET contenido='%s', path='%s' WHERE pdf_id=%d",
      pg_escape_string($pdf->getContenido()),
      pg_escape_string($pdf->getPath()),
      $pdf->getId()
    );

    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_errormessage($conn));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }

  public static function deletePdf(Pdf $pdf): void {
    $conn = Connection::getConnection();

    $query = sprintf("DELETE FROM pdf WHERE pdf_id=%d", $pdf->getId());

    if (!($rs = pg_query($conn, $query)))
      throw new Exception(pg_last_error($conn));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }
}
