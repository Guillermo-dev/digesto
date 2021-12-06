<?php

namespace models;

use JsonSerializable;
use models\exceptions\ModalException;

/**
 * Class Documento
 *
 * @package models
 */
class Documento implements JsonSerializable {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $numeroExpediente;

    /**
     * @var string
     */
    private $titulo;

    /**
     * @var string
     */
    private $descripcion;

    /**
     * @var string
     */
    private $fechaEmision;

    /**
     * @var bool
     */
    private $descargable;

    /**
     * @var bool
     */
    private $publico;

    /**
     * @var bool
     */
    private $derogado;

    /**
     * @var int
     */
    private $pdf_id;

    /**
     * @var int
     */
    private $tipo_id;

    /**
     * @var int
     */
    private $emisor_id;

     /**
     * @var int
     */
    private $derogado_id;

    /**
     * @var int
     */
    private $usuario_id;

    /**
     * Documento constructor.
     *
     * @param int    $id
     * @param string $numeroExpediente
     * @param string $titulo
     * @param string $descripcion
     * @param string $fechaEmision
     * @param bool   $descargable
     * @param bool   $publico
     * @param bool   $derogado
     * @param int    $pdf_id
     * @param int    $tipo_id
     * @param int    $emisor_id
     * @param int    $usuario_id
     */
    public function __construct(int $id = 0, string $numeroExpediente = '', string $titulo = '', string $descripcion = '', string $fechaEmision = '', bool $descargable = false, bool $publico = false, bool $derogado = false, int $pdf_id = 0, int $tipo_id = 0, int $emisor_id = 0,int $derogado_id = null, int $usuario_id = 0) {
        $this->id = $id;
        $this->numeroExpediente = $numeroExpediente;
        $this->titulo = $titulo;
        $this->descripcion = $descripcion;
        $this->fechaEmision = $fechaEmision;
        $this->descargable = $descargable;
        $this->publico = $publico;
        $this->derogado = $derogado;
        $this->pdf_id = $pdf_id;
        $this->tipo_id = $tipo_id;
        $this->emisor_id = $emisor_id;
        $this->derogado_id = $derogado_id;
        $this->usuario_id = $usuario_id;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNumeroExpediente(): string {
        return $this->numeroExpediente;
    }

    /**
     * @return string
     */
    public function getTitulo(): string {
        return $this->titulo;
    }

    /**
     * @return string
     */
    public function getDescripcion(): string {
        return $this->descripcion;
    }

    /**
     * @return string
     */
    public function getFechaEmision(): string {
        return $this->fechaEmision;
    }

    /**
     * @return bool
     */
    public function getDescargable(): bool {
        return $this->descargable;
    }

    /**
     * @return bool
     */
    public function getPublico(): bool {
        return $this->publico;
    }

    /**
     * @return bool
     */
    public function getDerogado(): bool {
        return $this->derogado;
    }

    /**
     * @return int
     */
    public function getPdfId(): int {
        return $this->pdf_id;
    }

    /**
     * @return int
     */
    public function getTipoId(): int {
        return $this->tipo_id;
    }

    /**
     * @return int
     */
    public function getEmisorId(): int {
        return $this->emisor_id;
    }

    /**
     * @return int
     */
    public function getDerogadoId(): int {
        return $this->derogado_id;
    }

    /**
     * @return int
     */
    public function getUsuarioId(): int {
        return $this->usuario_id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): Documento {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $numeroExpediente
     *
     * @return $this
     */
    public function setNumeroExpediente(string $numeroExpediente): Documento {
        $this->numeroExpediente = $numeroExpediente;
        return $this;
    }

    /**
     * @param string $titulo
     *
     * @return $this
     */
    public function setTitulo(string $titulo): Documento {
        $this->titulo = $titulo;
        return $this;
    }

    /**
     * @param string $descripcion
     *
     * @return $this
     */
    public function setDescripcion(string $descripcion): Documento {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * @param string $tipo
     *
     * @return $this
     */
    public function setTipo(string $tipo): Documento {
        $this->tipo = $tipo;
        return $this;
    }

    /**
     * @param string $fechaEmision
     *
     * @return $this
     */
    public function setFechaEmision(string $fechaEmision): Documento {
        $this->fechaEmision = $fechaEmision;
        return $this;
    }

    /**
     * @param bool $descargable
     *
     * @return $this
     */
    public function setDescargable(bool $descargable): Documento {
        $this->descargable = $descargable;
        return $this;
    }

    /**
     * @param bool $publico
     *
     * @return $this
     */
    public function setPublico(bool $publico): Documento {
        $this->publico = $publico;
        return $this;
    }

    /**
     * @param bool $derogado
     *
     * @return $this
     */
    public function setDerogado(bool $derogado): Documento {
        $this->derogado = $derogado;
        return $this;
    }

    /**
     * @param int $pdf_id
     *
     * @return $this
     */
    public function setPdfId(int $pdf_id): Documento {
        $this->pdf_id = $pdf_id;
        return $this;
    }

    /**
     * @param int $tipo_id
     *
     * @return $this
     */
    public function setTipoId(int $tipo_id): Documento {
        $this->tipo_id = $tipo_id;
        return $this;
    }

    /**
     * @param int $emisor_id
     *
     * @return $this
     */
    public function setEmisorId(int $emisor_id): Documento {
        $this->emisor_id = $emisor_id;
        return $this;
    }

        /**
     * @param int $derogado_id
     *
     * @return $this
     */
    public function setDerogadoId(?int $derogado_id): Documento {
        $this->derogado_id = $derogado_id;
        return $this;
    }

    /**
     * @param int $usuario_id
     *
     * @return $this
     */
    public function setUsuarioId(int $usuario_id): Documento {
        $this->usuario_id = $usuario_id;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return get_object_vars($this);
    }


    /********************************************************/

    /**
     * @param bool $onlyPublics
     *
     * @return array
     * @throws ModalException
     */
    public static function getDocumentos(bool $onlyPublics): array {
        $conn = Connection::getConnection();

        $query = sprintf(
            'SELECT documento_id, numero_expediente, titulo, descripcion, fecha_emision, descargable, publico, derogado, pdf_id, tipo_id, emisor_id, derogado_id
        FROM documentos WHERE publico = %s OR publico = TRUE ORDER BY  fecha_emision DESC, numero_expediente',
            $onlyPublics ? 'TRUE' : 'FALSE'
        );
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $documentos = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $documento = new Documento();
            $documento->setId($row['documento_id']);
            $documento->setNumeroExpediente($row['numero_expediente']);
            $documento->setTitulo($row['titulo']);
            $documento->setDescripcion(isset($row['descripcion']) ? $row['descripcion'] : 'No hay descripción de este documento');
            $documento->setFechaEmision($row['fecha_emision']);
            $documento->setDescargable($row['descargable'] === 't');
            $documento->setPublico($row['publico'] === 't');
            $documento->setDerogado($row['derogado']);
            $documento->setPdfId($row['pdf_id']);
            $documento->setTipoId($row['tipo_id']);
            $documento->setEmisorId($row['emisor_id']);
            $documento->setDerogadoId($row['derogado_id']);
            $documentos[] = $documento;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $documentos;
    }

    /**
     * @param string $str
     *
     * @return string
     */
    private static function lloro2elregreso(string $str): string {
        return '(\'' . implode('\',\'', explode(';', pg_escape_string($str))) . '\')';
    }

    /**
     * @param string $search
     * @param string $emitters
     * @param string $tags
     * @param string $years
     * @param bool   $onlyPublics
     *
     * @return array
     * @throws exceptions\ModalException
     */
    public static function getDocumentosSearch(string $search, string $emitters, string $tags, string $years, bool $onlyPublics, string $publicos, string $privados): array {
        $conn = Connection::getConnection();

        $query = sprintf(
            "SELECT DISTINCT D.documento_id, D.numero_expediente, D.titulo, D.descripcion, D.fecha_emision, D.descargable, D.publico, D.derogado, D.pdf_id, D.tipo_id, D.emisor_id, derogado_id 
            FROM documentos D 
            INNER JOIN emisores E ON D.emisor_id = E.emisor_id
            INNER JOIN tipos TI ON D.tipo_id = TI.tipo_id
            INNER JOIN documentos_tags DT ON DT.documento_id = D.documento_id
            INNER JOIN tags T ON DT.tag_id = T.tag_id
            WHERE (%s) 
                %s 
                %s 
                %s 
                %s
                %s
                ORDER BY  D.fecha_emision DESC, D.numero_expediente",
            $onlyPublics ? 'D.publico = TRUE' : 'D.publico = ' . $publicos . ' OR D.publico = ' . $privados . '',
            $search == '' ? '' : "AND (UPPER(D.titulo) LIKE UPPER('%" . pg_escape_string($search) . "%')",
            $search == '' ? '' : "OR UPPER(D.numero_expediente) LIKE '%" . pg_escape_string($search) . "%')",
            $emitters == '' ? '' : "AND E.nombre IN " . self::lloro2elregreso($emitters),
            $tags == '' ? '' : "AND T.nombre IN " . self::lloro2elregreso($tags),
            $years == '' ? '' : "AND CAST(EXTRACT(YEAR FROM D.fecha_emision) AS varchar) IN " . self::lloro2elregreso($years),
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $documentos = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $documento = new Documento();
            $documento->setId($row['documento_id']);
            $documento->setNumeroExpediente($row['numero_expediente']);
            $documento->setTitulo($row['titulo']);
            $documento->setDescripcion($row['descripcion'] != '' ? $row['descripcion'] : 'No hay descripción de este documento');
            $documento->setFechaEmision($row['fecha_emision']);
            $documento->setDescargable($row['descargable'] === 't');
            $documento->setPublico($row['publico'] === 't');
            $documento->setDerogado($row['derogado']);
            $documento->setPdfId($row['pdf_id']);
            $documento->setTipoId($row['tipo_id']);
            $documento->setEmisorId($row['emisor_id']);
            $documento->setDerogadoId($row['derogado_id']);
            $documentos[] = $documento;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $documentos;
    }

    /**
     * @param int $id
     *
     * @return Documento|null
     * @throws ModalException
     */
    public static function getDocumentoById(int $id): ?Documento {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT documento_id, numero_expediente, titulo, descripcion, fecha_emision, descargable, publico, derogado, pdf_id, tipo_id, emisor_id , derogado_id
    FROM documentos WHERE documento_id=%d', $id);
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $documento = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $documento = new Documento();
            $documento->setId($row['documento_id']);
            $documento->setNumeroExpediente($row['numero_expediente']);
            $documento->setTitulo($row['titulo']);
            $documento->setDescripcion($row['descripcion'] != '' ? $row['descripcion'] : 'No hay descripción de este documento');
            $documento->setFechaEmision($row['fecha_emision']);
            $documento->setDescargable($row['descargable'] === 't');
            $documento->setPublico($row['publico'] === 't');
            $documento->setDerogado($row['derogado']);
            $documento->setPdfId($row['pdf_id']);
            $documento->setTipoId($row['tipo_id']);
            $documento->setEmisorId($row['emisor_id']);
            $documento->setDerogadoId($row['derogado_id']);
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $documento;
    }

    /**
     * @param Documento $documento
     *
     * @throws ModalException
     */
    public static function createDocumento(Documento $documento): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "INSERT INTO documentos (numero_expediente, titulo, descripcion, fecha_emision, descargable, publico, derogado, pdf_id, tipo_id, emisor_id, derogado_id) 
      VALUES ('%s','%s','%s','%s',%s ,%s ,%s, %d ,%d ,%d, %d) RETURNING Currval('documentos_documento_id_seq')",
            pg_escape_string($documento->getNumeroExpediente()),
            pg_escape_string($documento->getTitulo()),
            pg_escape_string($documento->getDescripcion()),
            pg_escape_string($documento->getFechaEmision()),
            $documento->getDescargable() ? "TRUE" : "FALSE",
            $documento->getPublico() ? "TRUE" : "FALSE",
            $documento->getDerogado() ? "TRUE" : "FALSE",
            $documento->getPdfId(),
            $documento->getTipoId(),
            $documento->getEmisorId(),
            $documento->getDerogadoId(),
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        if (($row = pg_fetch_row($rs)))
            $documento->setId(($row[0]));
        else throw new ModalException(pg_last_error($rs));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param Documento $documento
     *
     * @throws ModalException
     */
    public static function updateDocumento(Documento $documento): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "UPDATE documentos SET numero_expediente='%s', titulo='%s', descripcion='%s', fecha_emision='%s', descargable=%s, publico=%s, derogado=%s, pdf_id=%d, tipo_id=%d, emisor_id=%d, derogado_id  
      WHERE documento_id=%d",
            pg_escape_string($documento->getNumeroExpediente()),
            pg_escape_string($documento->getTitulo()),
            pg_escape_string($documento->getDescripcion()),
            pg_escape_string($documento->getFechaEmision()),
            $documento->getDescargable() ? "TRUE" : "FALSE",
            $documento->getPublico() ? "TRUE" : "FALSE",
            $documento->getDerogado() ? "TRUE" : "FALSE",
            $documento->getPdfId(),
            $documento->getTipoId(),
            $documento->getEmisorId(),
            $documento->getDerogadoId(),
            $documento->getId(),
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_errormessage($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param int $documentoId
     *
     * @throws ModalException
     */
    public static function deleteDocumento(int $documentoId): void {
        $conn = Connection::getConnection();

        $query = sprintf("DELETE FROM documentos WHERE documento_id=%d", $documentoId);

        if (!($rs = pg_query($conn, $query)))
            throw new ModalException(pg_last_error($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param int $documentoId
     * @param int $tagId
     *
     * @throws ModalException
     */
    public static function assignTagDocumento(int $documentoId, int $tagId): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            'INSERT INTO documentos_tags (documento_id, tag_id) VALUES (%d, %d)',
            $documentoId,
            $tagId,
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    public static function clearTagDocumento(int $documentoId): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            'DELETE FROM documentos_tags WHERE documento_id = %d',
            $documentoId,
        );
        if (!($rs = pg_query($conn, $query)))
            throw new ModalException(pg_last_error($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }
}
