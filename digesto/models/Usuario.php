<?php

namespace models;

use JsonSerializable;
use models\exceptions\ModalException;

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
     * @var bool
     */
    private $admin;

    /**
     * Usuario constructor.
     *
     * @param int    $id
     * @param string $email
     * @param string $nombre
     * @param bool   $admin
     */
    public function __construct(int $id = 0, string $email = '', string $nombre = '', bool $admin = false) {
        $this->id = $id;
        $this->email = $email;
        $this->nombre = $nombre;
        $this->admin = $admin;
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
     * @return bool
     */
    public function getAdmin(): bool {
        return $this->admin;
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
     * @param bool $admin
     *
     * @return $this
     */
    public function setAdmin(bool $admin): Usuario {
        $this->admin = $admin;
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
    public static function getUsuarios(): array {
        $conn = Connection::getConnection();

        $query = 'SELECT usuario_id, email, nombre, admin FROM usuarios';
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $usuarios = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $usuario = new Usuario();
            $usuario->setId($row['usuario_id']);
            $usuario->setEmail($row['email']);
            $usuario->setNombre($row['nombre']);
            $usuario->setAdmin($row['admin']);
            $usuarios[] = $usuario;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $usuarios;
    }

    /**
     * @param int $id
     *
     * @return Usuario|null
     * @throws ModalException
     */
    public static function getUsuarioById(int $id): ?Usuario {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT usuario_id, email, nombre, admin FROM usuarios WHERE usuario_id = %d', $id);
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $usuario = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $usuario = new Usuario();
            $usuario->setId($row['usuario_id']);
            $usuario->setEmail($row['email']);
            $usuario->setNombre($row['nombre']);
            $usuario->setAdmin($row['admin'] == 't');
        }
        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $usuario;
    }

    /**
     * @param string $email
     *
     * @return Usuario|null
     * @throws ModalException
     */
    public static function getUsuarioByEmail(string $email): ?Usuario {
        $conn = Connection::getConnection();

        $query = sprintf(
            "SELECT usuario_id, email, nombre, admin FROM usuarios WHERE email = '%s'",
            pg_escape_string($email)
        );
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $usuario = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $usuario = new Usuario();
            $usuario->setId($row['usuario_id']);
            $usuario->setEmail($row['email']);
            $usuario->setNombre($row['nombre']);
            $usuario->setAdmin($row['admin']);
        }
        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $usuario;
    }

    /**
     * @param Usuario $usuario
     *
     * @throws ModalException
     */
    public static function createUsuario(Usuario $usuario): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "INSERT INTO usuarios (email, nombre, admin) VALUES ('%s', '%s', FALSE) RETURNING Currval('usuarios_usuario_id_seq')",
            pg_escape_string($usuario->getEmail()),
            pg_escape_string($usuario->getNombre()),
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        if (($row = pg_fetch_row($rs)))
            $usuario->setId(($row[0]));
        else throw new ModalException(pg_last_error($rs));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param Usuario $usuario
     *
     * @throws ModalException
     */
    public static function updateUsuario(Usuario $usuario): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "UPDATE usuarios SET email='%s' ,nombre='%s' WHERE usuario_id='%d'",
            pg_escape_string($usuario->getEmail()),
            pg_escape_string($usuario->getNombre()),
            $usuario->getId()
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_errormessage($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param int $usuario_id
     *
     * @throws ModalException
     */
    public static function deleteUsuario(int $usuario_id): void {
        $conn = Connection::getConnection();

        $query = sprintf("DELETE FROM usuarios WHERE usuario_id='%s'", $usuario_id);

        $rs = @pg_query($conn, $query);
        if ($rs === false)
            throw new ModalException(pg_last_error($conn), Connection::getErrorCode());

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }
}
