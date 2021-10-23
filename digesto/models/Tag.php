<?php

namespace models;

use Exception;
use JsonSerializable;

class Tag implements JsonSerializable {

  private $id;

  private $nombre;

  public function __construct(int $id = 0, string $nombre = '') {
    $this->id = $id;
    $this->nombre = $nombre;
  }

  public function getId() {
    return $this->id;
  }

  public function getNombre() {
    return $this->nombre;
  }

  public function setId(int $id) {
    $this->id = $id;
    return $this;
  }

  public function setNombre(string $nombre) {
    $this->nombre = $nombre;
    return $this;
  }

  public function jsonSerialize(): array {
    return get_object_vars($this);
  }

  /********************************************************/

  public static function getTags(): array {
    $conn = Connection::getConnection();

    $query = 'SELECT tag_id, nombre FROM tags';
    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_last_error($conn));

    $tags = [];
    while (($row = pg_fetch_assoc($rs)) != false) {
      $tag = new Tag();
      $tag->setId($row['tag_id']);
      $tag->setNombre($row['nombre']);
      $tags[] = $tag;
    }

    if (($error = pg_last_error($conn)) != false)
      throw new Exception($error);

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));

    return $tags;
  }

  public static function getTagById(int $id): ?Tag {
    $conn = Connection::getConnection();

    $query = sprintf('SELECT tag_id, nombre FROM tags WHERE tag_id = %d', $id);
    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_last_error($conn));

    $tag = null;
    if (($row = pg_fetch_assoc($rs)) != false) {
      $tag = new Tag();
      $tag->setId($row['tag_id']);
      $tag->setNombre($row['nombre']);
    }
    if (($error = pg_last_error($conn)) != false)
      throw new Exception($error);

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error());

    return $tag;
  }

  public static function createTag(Tag $tag): void {
    $conn = Connection::getConnection();

    $query = sprintf(
      "INSERT INTO tags (nombre) VALUES ('%s') RETURNING Currval('tags_tag_id_seq')",
      pg_escape_string($tag->getNombre()),
    );

    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_last_error($conn));

    if (($row = pg_fetch_row($rs)))
      $tag->setId(($row[0]));
    else throw new Exception(pg_last_error($rs));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }

  public static function updateTag(Tag $tag): void {
    $conn = Connection::getConnection();

    $query = sprintf(
      "UPDATE tag SET nombre='%s' WHERE tag_id=%d",
      pg_escape_string($tag->getNombre()),
      $tag->getId()
    );

    if (($rs = pg_query($conn, $query)) === false)
      throw new Exception(pg_errormessage($conn));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }

  public static function deleteTag(Tag $tag): void {
    $conn = Connection::getConnection();

    $query = sprintf("DELETE FROM tags WHERE tag_id=%d", $tag->getId());

    if (!($rs = pg_query($conn, $query)))
      throw new Exception(pg_last_error($conn));

    if (!pg_free_result($rs))
      throw new Exception(pg_last_error($conn));
  }

  public static function getTagsByDocumento(Documento $documento): array {
    $conn = Connection::getConnection();

    $query = sprintf("SELECT T.tag_id, T.nombre
        FROM tags T INNER JOIN documentos_tags D ON D.tag_id = T.tag_id
        WHERE D.documento_id = %d", $documento->getId());
        if (($rs = pg_query($conn, $query)) === false)
            throw new Exception(pg_last_error($conn));

        $tags = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $tag = new Tag();
            $tag->setId($row['tag_id']);
            $tag->setNombre($row['nombre']);
            $tags[] = $tag;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new Exception($error);

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error($conn));

        return $tags;
  }
}
