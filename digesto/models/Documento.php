<?php

namespace models;

use Exception;
use JsonSerializable;

class Documento implements JsonSerializable {

  private $id;

  private $numeroExpediente;

  private $titulo;

  private $descripcion;

  private $tipo;

  private $fechaEmision;

  private $descargable;

  private $publico;

  private $pdf_id;

  private $emisor_id;

  private $usuario_id;

  public function __construct(int $id = 0, string $numeroExpediente = '', string $titulo = '', string $descripcion = '', string $tipo = '', string $fechaEmision = '', bool $descargable = false, bool $publico = false, int $pdf_id = 0, int $emisor_id = 0, int $usuario_id = 0) {
    $this->id = $id;
    $this->numeroExpediente = $numeroExpediente;
    $this->titulo = $titulo;
    $this->descripcion = $descripcion;
    $this->tipo = $tipo;
    $this->fechaEmision = $fechaEmision;
    $this->descargable = $descargable;
    $this->publico = $publico;
    $this->pdf_id = $pdf_id;
    $this->emisor_id = $emisor_id;
    $this->usuario_id = $usuario_id;
  }

  public function getId(): int {
    return $this->id;
  }

  public function getNumeroExpediente(): string {
    return $this->numeroExpediente;
  }

  public function getTitulo(): string {
    return $this->titulo;
  }

  public function getDescripcion(): string {
    return $this->descripcion;
  }

  public function getTipo(): string {
    return $this->tipo;
  }

  public function getFechaEmision(): string {
    return $this->fechaEmision;
  }

  public function getDescargable(): bool {
    return $this->descargable;
  }

  public function getPublico(): bool {
    return $this->publico;
  }

  public function getPdfId(): int {
    return $this->pdf_id;
  }

  public function getEmisorId(): int {
    return $this->emisor_id;
  }

  public function getUsuarioId(): int {
    return $this->usuario_id;
  }

  public function setId(int $id): Documento {
    $this->id = $id;
    return $this;
  }

  public function setNumeroExpediente(string $numeroExpediente): Documento {
    $this->numeroExpediente = $numeroExpediente;
    return $this;
  }

  public function setTitulo(string $titulo): Documento {
    $this->titulo = $titulo;
    return $this;
  }

  public function setDescripcion(string $descripcion): Documento {
    $this->descripcion = $descripcion;
    return $this;
  }

  public function setTipo(string $tipo): Documento {
    $this->tipo = $tipo;
    return $this;
  }

  public function setFechaEmision(string $fechaEmision): Documento {
    $this->fechaEmision = $fechaEmision;
    return $this;
  }

  public function setDescargable(bool $descargable): Documento {
    $this->descargable = $descargable;
    return $this;
  }

  public function setPublico(bool $publico): Documento {
    $this->publico = $publico;
    return $this;
  }

  public function setPdfId(int $pdf_id): Documento {
    $this->pdf_id = $pdf_id;
    return $this;
  }

  public function setEmisorId(int $emisor_id): Documento {
    $this->emisor_id = $emisor_id;
    return $this;
  }

  public function setUsuarioId(int $usuario_id): Documento {
    $this->usuario_id = $usuario_id;
    return $this;
  }

  public function jsonSerialize(): array {
    return get_object_vars($this);
  }


  /********************************************************/

  public static function getDocumentos(): array {
    $conn = Connection::getConnection();

    $query = 'SELECT documento_id, numero_expediente, titulo, descripcion, tipo, fecha_emision, descargable, publico, pdf_id, emisor_id,usuario_id FROM documentos';
    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_last_error($conn));

    $documentos = [];
    while (($row = pg_fetch_assoc($rs)) != false) {
      $documento = new Documento();
      $documento->setId($row['documento_id']);
      $documento->setNumeroExpediente($row['numero_expediente']);
      $documento->setTitulo($row['titulo']);
      $documento->setDescripcion($row['descripcion']);
      $documento->setTipo($row['tipo']);
      $documento->setFechaEmision($row['fecha_emision']);
      $documento->setDescargable($row['descargable']);
      $documento->setPublico($row['publico']);
      $documento->setPdfId($row['pdf_id']);
      $documento->setEmisorId($row['emisor_id']);
      $documento->setUsuarioId($row['usuario_id']);
      $documentos[] = $documento;
    }

    if (($error = pg_last_error($conn)) != false)
      throw new Exception($error);

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));

    return $documentos;
  }

  public static function getDocumentoById(int $id): ?Documento {
    $conn = Connection::getConnection();

    $query = sprintf('SELECT documento_id, numero_expediente, titulo, descripcion, tipo, fecha_emision, descargable, publico, pdf_id, emisor_id,usuario_id FROM documentos WHERE documento_id=%d', $id);
    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_last_error($conn));

    $documento = null;
    if (($row = pg_fetch_assoc($rs)) != false) {
      $documento = new Documento();
      $documento->setId($row['documento_id']);
      $documento->setNumeroExpediente($row['numero_expediente']);
      $documento->setTitulo($row['titulo']);
      $documento->setDescripcion($row['descripcion']);
      $documento->setTipo($row['tipo']);
      $documento->setFechaEmision($row['fecha_emision']);
      $documento->setDescargable($row['descargable']);
      $documento->setPublico($row['publico']);
      $documento->setPdfId($row['pdf_id']);
      $documento->setEmisorId($row['emisor_id']);
      $documento->setUsuarioId($row['usuario_id']);
    }

    if (($error = pg_last_error($conn)) != false)
      throw new Exception($error);

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error());

    return $documento;
  }

  public static function createDocumento(Documento $documento): void {
    $conn = Connection::getConnection();

    $query = sprintf(
      "INSERT INTO documentos (numero_expediente, titulo, descripcion, tipo, fecha_emision, descargable, publico, pdf_id, emisor_id, usuario_id) VALUES ('%s','%s','%s','%s','%s',%s,%s,%d,%d, %d) RETURNING Currval('documentos_documento_id_seq')",
      pg_escape_string($documento->getNumeroExpediente()),
      pg_escape_string($documento->getTitulo()),
      pg_escape_string($documento->getDescripcion()),
      pg_escape_string($documento->getTipo()),
      pg_escape_string($documento->getFechaEmision()),
      $documento->getDescargable() ? "TRUE" : "FALSE",
      $documento->getPublico() ? "TRUE" : "FALSE",
      $documento->getPdfId(),
      $documento->getEmisorId(),
      $documento->getUsuarioId()
    );

    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_last_error($conn));

    if (($row = pg_fetch_row($rs)))
      $documento->setId(($row[0]));
    else throw new Exception(pg_last_error($rs));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }

  public static function updateDocumento(Documento $documento): void {
    $conn = Connection::getConnection();

    $query = sprintf(
      "UPDATE documentos SET numero_expediente='%s', titulo='%s', descripcion='%s', tipo='%s', fecha_emision='%s', descargable=%s, publico=%s, pdf_id=%d, emisor_id=%d, usuario_id=%d WHERE documento_id=%d",
      pg_escape_string($documento->getNumeroExpediente()),
      pg_escape_string($documento->getTitulo()),
      pg_escape_string($documento->getDescripcion()),
      pg_escape_string($documento->getTipo()),
      pg_escape_string($documento->getFechaEmision()),
      $documento->getDescargable() ? "TRUE" : "FALSE",
      $documento->getPublico() ? "TRUE" : "FALSE",
      $documento->getPdfId(),
      $documento->getEmisorId(),
      $documento->getUsuarioId(),
      $documento->getId()
    );

    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_errormessage($conn));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }

  public static function deleteDocumento(Documento $documento): void {
    $conn = Connection::getConnection();

    $query = sprintf("DELETE FROM documentos WHERE documento_id=%d", $documento->getId());

    if (!($rs = pg_query($conn, $query)))
      throw new Exception(pg_last_error($conn));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }
}
