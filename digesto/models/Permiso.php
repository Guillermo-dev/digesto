<?php

namespace models;

use JsonSerializable;
use models\exceptions\ModalException;

/**
 * Class Permiso
 *
 * @package models
 */
class Permiso implements JsonSerializable {
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $nombre;
    /**
     * @var string
     */
    private $descripcion;

    /**
     * Permiso constructor.
     *
     * @param int    $id
     * @param string $nombre
     * @param string $descripcion
     */
    public function __construct(int $id = 0, string $nombre = '', string $descripcion = '') {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->descripcion = $descripcion;
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
     * @return string
     */
    public function getDescripcion(): string {
        return $this->descripcion;
    }

    /**
     * @param int $id
     *
     * @return Permiso
     */
    public function setId(int $id): Permiso {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $nombre
     *
     * @return Permiso
     */
    public function setNombre(string $nombre): Permiso {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @param string $descripcion
     *
     * @return Permiso
     */
    public function setDescripcion(string $descripcion): Permiso {
        $this->descripcion = $descripcion;
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
    public static function getPermisos(): array {
        $conn = Connection::getConnection();

        $query = 'SELECT permiso_id, nombre, descripcion FROM permisos';
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $permisos = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $permiso = new Permiso();
            $permiso->setId($row['permiso_id']);
            $permiso->setNombre($row['nombre']);
            $permiso->setDescripcion($row['descripcion']);
            $permisos[] = $permiso;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $permisos;
    }

    /**
     * @param int $id
     *
     * @return Permiso|null
     * @throws ModalException
     */
    public static function getPermisoById(int $id): ?Permiso {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT permiso_id, nombre, descripcion FROM permisos WHERE permiso_id=%d', $id);
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $permiso = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $permiso = new Permiso();
            $permiso->setId($row['permiso_id']);
            $permiso->setNombre($row['nombre']);
            $permiso->setDescripcion($row['descripcion']);
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $permiso;
    }

    /**
     * @param Permiso $permiso
     *
     * @throws ModalException
     */
    public static function createPermiso(Permiso $permiso): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "INSERT INTO permisos (nombre, descripcion) VALUES ('%s','%s') RETURNING Currval('permisos_permiso_id_seq')",
            pg_escape_string($permiso->getNombre()),
            pg_escape_string($permiso->getDescripcion())
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        if (($row = pg_fetch_row($rs)))
            $permiso->setId(($row[0]));
        else throw new ModalException(pg_last_error($rs));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param Permiso $permiso
     *
     * @throws ModalException
     */
    public static function updatePermiso(Permiso $permiso): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "UPDATE permisos SET nombre='%s', descripcion='%s' WHERE permiso_id=%d",
            pg_escape_string($permiso->getNombre()),
            pg_escape_string($permiso->getDescripcion()),
            $permiso->getId()
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_errormessage($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param int $permiso_id
     *
     * @throws ModalException
     */
    public static function deletePermiso(int $permiso_id): void {
        $conn = Connection::getConnection();

        $query = sprintf("DELETE FROM permisos WHERE permiso_id=%d", $permiso_id);

        if (!($rs = pg_query($conn, $query)))
            throw new ModalException(pg_last_error($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param Usuario $usuario
     *
     * @return array
     * @throws ModalException
     */
    public static function getPermisosByUsuario(Usuario $usuario): array {
        $conn = Connection::getConnection();

        $query = sprintf("SELECT P.permiso_id, P.nombre, P.descripcion 
        FROM permisos P INNER JOIN usuarios_permisos U ON U.permiso_id = P.permiso_id
        WHERE U.usuario_id = %d", $usuario->getId());

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $permisos = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $permiso = new Permiso();
            $permiso->setId($row['permiso_id']);
            $permiso->setNombre($row['nombre']);
            $permiso->setDescripcion($row['descripcion']);
            $permisos[] = $permiso;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $permisos;
    }

    /**
     * @param string $permiso
     * @param int    $usuarioId
     *
     * @return bool
     * @throws ModalException
     */
    public static function hasPermiso(string $permiso, int $usuarioId): bool {
        $conn = Connection::getConnection();

        $query = sprintf(
            "SELECT P.nombre FROM usuarios_permisos U INNER JOIN permisos P ON U.permiso_id = P.permiso_id WHERE U.usuario_id = %d AND P.nombre = '%s'",
            $usuarioId,
            $permiso
        );
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $found = false;
        if (pg_fetch_assoc($rs) != false)
            $found = true;

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $found;
    }

    /**
     * @param Usuario $usuario
     * @param array   $permisos
     *
     * @throws ModalException
     */
    public static function assignPermisoToUsuario(Usuario $usuario, array $permisos): void {
        $conn = Connection::getConnection();

        if (pg_prepare($conn, '', "INSERT INTO usuarios_permisos (usuario_id, permiso_id) VALUES ($1,$2)") === false)
            throw new ModalException(pg_last_error($conn));

        foreach ($permisos as $permiso) {
            if (($rs = pg_execute($conn, '', [$usuario->getId(), $permiso])) === false)
                throw new ModalException(pg_last_error($conn));

            if (!pg_free_result($rs))
                throw new ModalException(pg_last_error($conn));
        }
    }

    /**
     * @param Usuario $usuario
     * @param array   $permisos
     *
     * @throws ModalException
     */
    public static function removePermisoFromUsuario(Usuario $usuario, array $permisos): void {
        $conn = Connection::getConnection();

        if (pg_prepare($conn, '', "DELETE FROM usuarios_permisos WHERE usuario_id=$1 AND permiso_id=$2") === false)
            throw new ModalException(pg_last_error($conn));

        foreach ($permisos as $permiso) {
            if (($rs = pg_execute($conn, '', [$usuario->getId(), $permiso])) === false)
                throw new ModalException(pg_last_error($conn));

            if (!pg_free_result($rs))
                throw new ModalException(pg_last_error($conn));
        }
    }
}
