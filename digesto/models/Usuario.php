<?php

namespace models;

use Exception;
use JsonSerializable;

/**
 * Class Usuario
 *
 * @package models
 */
class Usuario implements JsonSerializable {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $apellido;

    /**
     * Usuario constructor.
     *
     * @param int    $id
     * @param string $email
     * @param string $nombre
     * @param string $apellido
     */
    public function __construct(int $id = 0, string $email = '', string $nombre = '', string $apellido = '') {
        $this->id = $id;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
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
    public function getEmail(): string {
        return $this->email;
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
    public function getApelido(): string {
        return $this->apellido;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id): Usuario {
        $this->id = $id;
        return $this;
    }

    /**
     * @param String $email
     *
     * @return $this
     */
    public function setEmail(string $email): Usuario {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $nombre
     *
     * @return $this
     */
    public function setNombre(string $nombre): Usuario {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @param string $apellido
     *
     * @return $this
     */
    public function setApellido(string $apellido): Usuario {
        $this->apellido = $apellido;
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
     * @throws Exception
     */
    public static function getUsuarios(): array {
        $conn = Connection::getConnection();

        $query = 'SELECT usuario_id, email, nombre, apellido FROM usuarios';
        if (($rs = pg_query($conn, $query)) === false)
            throw new Exception(pg_last_error($conn));

        $usuarios = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $usuario = new Usuario();
            $usuario->setId($row['usuario_id']);
            $usuario->setEmail($row['email']);
            $usuario->setNombre($row['nombre']);
            $usuario->setApellido($row['apellido']);
            $usuarios[] = $usuario;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new Exception($error);

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error($conn));

        return $usuarios;
    }

    /**
     * @param int $id
     *
     * @return Usuario|null
     * @throws Exception
     */
    public static function getUsuarioById(int $id): ?Usuario {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT usuario_id, email, nombre, apellido FROM usuarios WHERE usuario_id = %d', $id);
        if (($rs = pg_query($conn, $query)) === false)
            throw new Exception(pg_last_error($conn));

        $usuario = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $usuario = new Usuario();
            $usuario->setId($row['usuario_id']);
            $usuario->setEmail($row['email']);
            $usuario->setNombre($row['nombre']);
            $usuario->setApellido($row['apellido']);
        }
        if (($error = pg_last_error($conn)) != false)
            throw new Exception($error);

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error());

        return $usuario;
    }

    /**
     * @param Usuario $usuario
     *
     * @throws Exception
     */
    public static function createUsuario(Usuario $usuario): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "INSERT INTO usuarios (email, nombre, apellido) VALUES ('%s', '%s', '%s') RETURNING Currval('usuarios_usuario_id_seq')",
            pg_escape_string($usuario->getEmail()),
            pg_escape_string($usuario->getNombre()),
            pg_escape_string($usuario->getApelido())
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new Exception(pg_last_error($conn));

        if (($row = pg_fetch_row($rs)))
            $usuario->setId(($row[0]));
        else throw new Exception(pg_last_error($rs));

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error($conn));
    }

    /**
     * @param Usuario $usuario
     *
     * @throws Exception
     */
    public static function updateUsuario(Usuario $usuario): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "UPDATE usuarios SET email='%s' ,nombre='%s', apellido='%s' WHERE usuario_id='%d'",
            pg_escape_string($usuario->getEmail()),
            pg_escape_string($usuario->getNombre()),
            pg_escape_string($usuario->getApelido()),
            $usuario->getId()
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new Exception(pg_errormessage($conn));

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error($conn));
    }

    /**
     * @param Usuario $usuario
     *
     * @throws Exception
     */
    public static function deleteUsuario(Usuario $usuario): void {
        $conn = Connection::getConnection();

        $query = sprintf("DELETE FROM usuarios WHERE usuario_id='%s'", $usuario->getId());

        if (!($rs = pg_query($conn, $query)))
            throw new Exception(pg_last_error($conn));

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error($conn));
    }
}
