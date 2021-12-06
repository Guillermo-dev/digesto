<?php

namespace models;

use JsonSerializable;
use models\exceptions\ModalException;

/**
 * Class TIpo
 *
 * @package models
 */
class Tipo implements JsonSerializable {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * Emisor constructor.
     *
     * @param int    $id
     * @param string $nombre
     */
    public function __construct(int $id = 0, string $nombre = '') {
        $this->id = $id;
        $this->nombre = $nombre;
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
    public function getNombre(): string {
        return $this->nombre;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): Tipo {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $nombre
     *
     * @return $this
     */
    public function setNombre(string $nombre): Tipo {
        $this->nombre = $nombre;
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
     * @return array
     * @throws ModalException
     */
    public static function getTipos(): array {
        $conn = Connection::getConnection();

        $query = 'SELECT tipo_id, nombre FROM tipos ORDER BY nombre';
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $tipos = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $tipo = new Tipo();
            $tipo->setId($row['tipo_id']);
            $tipo->setNombre($row['nombre']);
            $tipos[] = $tipo;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $tipos;
    }

        /**
     * @param int $id
     *
     * @return Tipo|null
     * @throws ModalException
     */
    public static function getTipoById(int $id): ?Tipo {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT tipo_id, nombre FROM tipos WHERE tipo_id=%d', $id);
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $tipo = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $tipo = new Tipo();
            $tipo->setId($row['tipo_id']);
            $tipo->setNombre($row['nombre']);
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $tipo;
    }

    /**
     * @param string $nombre
     *
     * @return Tipo|null
     * @throws ModalException
     */
    public static function getTipoBynombre(string $nombre): ?Tipo {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT tipo_id, nombre FROM tipos WHERE nombre=\'%s\'', $nombre);
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $tipo = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $tipo = new Emisor();
            $tipo->setId($row['tipo_id']);
            $tipo->setNombre($row['nombre']);
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $tipo;
    }

    /**
     * @param Tipo $tipo
     *
     * @throws ModalException
     */
    public static function createTipo(Tipo $tipo): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "INSERT INTO tipos (nombre) VALUES ('%s') RETURNING Currval('emisores_emisor_id_seq')",
            pg_escape_string($tipo->getNombre()),
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        if (($row = pg_fetch_row($rs)))
            $tipo->setId(($row[0]));
        else throw new ModalException(pg_last_error($rs));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param Tipo $tipo
     *
     * @throws ModalException
     */
    public static function updateTipo(Tipo $tipo): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "UPDATE tipos SET nombre='%s' WHERE tipo_id=%d",
            pg_escape_string($tipo->getNombre()),
            $tipo->getId()
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_errormessage($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param int $tipo_id
     *
     * @throws ModalException
     */
    public static function deleteTipo(int $tipo_id): void {
        $conn = Connection::getConnection();

        $query = sprintf("DELETE FROM tipos WHERE tipo_id=%d", $tipo_id);

        if (!($rs = pg_query($conn, $query)))
            throw new ModalException(pg_last_error($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }
}