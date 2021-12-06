<?php

namespace models;

use JsonSerializable;
use models\exceptions\ModalException;

/**
 * Class Emisor
 *
 * @package models
 */
class Emisor implements JsonSerializable {

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
    public function setId(int $id): Emisor {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $nombre
     *
     * @return $this
     */
    public function setNombre(string $nombre): Emisor {
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
    public static function getEmisores(): array {
        $conn = Connection::getConnection();

        $query = 'SELECT emisor_id, nombre FROM emisores ORDER BY nombre';
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $emisores = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $emisor = new Emisor();
            $emisor->setId($row['emisor_id']);
            $emisor->setNombre($row['nombre']);
            $emisores[] = $emisor;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $emisores;
    }

    /**
     * @param int $id
     *
     * @return Emisor|null
     * @throws ModalException
     */
    public static function getEmisorById(int $id): ?Emisor {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT emisor_id, nombre FROM emisores WHERE emisor_id=%d', $id);
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $emisor = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $emisor = new Emisor();
            $emisor->setId($row['emisor_id']);
            $emisor->setNombre($row['nombre']);
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $emisor;
    }

    /**
     * @param string $nombre
     *
     * @return Emisor|null
     * @throws ModalException
     */
    public static function getEmisorBynombre(string $nombre): ?Emisor {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT emisor_id, nombre FROM emisores WHERE nombre=\'%s\'', $nombre);
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $emisor = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $emisor = new Emisor();
            $emisor->setId($row['emisor_id']);
            $emisor->setNombre($row['nombre']);
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $emisor;
    }

    /**
     * @param Emisor $emisor
     *
     * @throws ModalException
     */
    public static function createEmisor(Emisor $emisor): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "INSERT INTO emisores (nombre) VALUES ('%s') RETURNING Currval('emisores_emisor_id_seq')",
            pg_escape_string($emisor->getNombre()),
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        if (($row = pg_fetch_row($rs)))
            $emisor->setId(($row[0]));
        else throw new ModalException(pg_last_error($rs));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param Emisor $emisor
     *
     * @throws ModalException
     */
    public static function updateEmisor(Emisor $emisor): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "UPDATE emisores SET nombre='%s' WHERE emisor_id=%d",
            pg_escape_string($emisor->getNombre()),
            $emisor->getId()
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_errormessage($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param int $emisor_id
     *
     * @throws ModalException
     */
    public static function deleteEmisor(int $emisor_id): void {
        $conn = Connection::getConnection();

        $query = sprintf("DELETE FROM emisores WHERE emisor_id=%d", $emisor_id);

        if (!($rs = pg_query($conn, $query)))
            throw new ModalException(pg_last_error($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }
}
