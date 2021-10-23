<?php

namespace models;

use Exception;
use JsonSerializable;

class Usuario  implements JsonSerializable {

  private $id;

  private $email;

  private $nombre;

  private $apellido;

  public function __construct(int $id = 0, string $email = '', string $nombre = '', string $apellido = '') {
    $this->id = $id;
    $this->emial = $email;
    $this->nombre = $nombre;
    $this->apellido = $apellido;
  }

  public function getId() {
    return $this->id;
  }

  public function getEmail() {
    return $this->email;
  }

  public function getNombre() {
    return $this->nombre;
  }

  public function getApelido() {
    return $this->apellido;
  }

  public function setId(int $id) {
    $this->id = $id;
    return $this;
  }

  public function setEmail(String $email) {
    $this->email = $email;
    return $this;
  }

  public function setNombre(string $nombre) {
    $this->nombre = $nombre;
    return $this;
  }

  public function setApellido(string $apellido) {
    $this->apellido = $apellido;
    return $this;
  }

  public function jsonSerialize(): array {
    return get_object_vars($this);
  }

  /********************************************************/

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

  public static function getUsuarioById(int $id): ?Usuario {
    $conn = Connection::getConnection();

    $query = sprintf('SELECT usuario_id, email, nombre, apellido FROM usuarios WHERE usuario_id=%id', $id);
    if (($rs = pg_query($conn, $query)) == false)
      throw new Exception(pg_last_error($conn));

    $usuario = null;
    if (($row = pg_fetch_assoc($rs)) != false) {
      $usuario = new Usuario();
      $usuario->setID($row['usuario_id']);
      $usuario->setEmail($row['email']);
      $usuario->setNombre($row['nombre']);
      $usuario->setApellido($row['apellido']);
    }

    if (($error = pg_last_error($conn)) != false)
      throw new Exception($error);

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));

    return $usuario;
  }

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

  public static function deleteUsuario(Usuario $usuario): void {
    $conn = Connection::getConnection();

    $query = sprintf("DELETE FROM usuarios WHERE usuario_id='%s'", $usuario->getId());

    if (!($rs = pg_query($conn, $query)))
      throw new Exception(pg_last_error($conn));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }
}
