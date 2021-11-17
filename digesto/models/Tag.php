<?php

namespace models;

use JsonSerializable;
use models\exceptions\ModalException;

/**
 * Class Tag
 *
 * @package models
 */
class Tag implements JsonSerializable {

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $nombre;

    /**
     * Tag constructor.
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
    public function setId(int $id): Tag {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $nombre
     *
     * @return $this
     */
    public function setNombre(string $nombre): Tag {
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
    public static function getTags(): array {
        $conn = Connection::getConnection();

        $query = 'SELECT tag_id, nombre FROM tags';
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $tags = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $tag = new Tag();
            $tag->setId($row['tag_id']);
            $tag->setNombre($row['nombre']);
            $tags[] = $tag;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $tags;
    }

    /**
     * @param int $id
     *
     * @return Tag|null
     * @throws ModalException
     */
    public static function getTagById(int $id): ?Tag {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT tag_id, nombre FROM tags WHERE tag_id = %d', $id);
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $tag = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $tag = new Tag();
            $tag->setId($row['tag_id']);
            $tag->setNombre($row['nombre']);
        }
        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $tag;
    }

    /**
     * @param string $nombre
     *
     * @return Tag|null
     * @throws ModalException
     */
    public static function getTagByName(string $name): ?Tag {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT tag_id, nombre FROM tags WHERE nombre = %d', $name);
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $tag = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $tag = new Tag();
            $tag->setId($row['tag_id']);
            $tag->setNombre($row['nombre']);
        }
        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $tag;
    }

    /**
     * @param Tag $tag
     *
     * @throws ModalException
     */
    public static function createTag(Tag $tag): void {
        $conn = Connection::getConnection();

        $lastId = pg_getlastoid($query = sprintf(
            "INSERT INTO tags (nombre) VALUES ('%s') RETURNING Currval('tags_tag_id_seq')",
            pg_escape_string(strtolower($tag->getNombre())),
        ));

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        if (($row = pg_fetch_row($rs)))
            $tag->setId(($row[0]));
        else throw new ModalException(pg_last_error($rs));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        $tag->setId($lastId);
    }

    /**
     * @param Tag $tag
     *
     * @throws ModalException
     */
    public static function updateTag(Tag $tag): void {
        $conn = Connection::getConnection();

        $query = sprintf(
            "UPDATE tag SET nombre='%s' WHERE tag_id=%d",
            pg_escape_string(strtolower($tag->getNombre())),
            $tag->getId()
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_errormessage($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param int $tag_id
     *
     * @throws ModalException
     */
    public static function deleteTag(int $tag_id): void {
        $conn = Connection::getConnection();

        $query = sprintf("DELETE FROM tags WHERE tag_id=%d", $tag_id);

        if (!($rs = pg_query($conn, $query)))
            throw new ModalException(pg_last_error($conn));

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));
    }

    /**
     * @param Documento $documento
     *
     * @return array
     * @throws ModalException
     */
    public static function getTagsByDocumento(Documento $documento): array {
        $conn = Connection::getConnection();

        $query = sprintf("SELECT T.tag_id, T.nombre
        FROM tags T INNER JOIN documentos_tags D ON D.tag_id = T.tag_id
        WHERE D.documento_id = %d", $documento->getId());
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $tags = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $tag = new Tag();
            $tag->setId($row['tag_id']);
            $tag->setNombre($row['nombre']);
            $tags[] = $tag;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error($conn));

        return $tags;
    }

    /**
     * @param string $text
     *
     * @return Tag|null
     * @throws ModalException
     */
    public static function getTagByText(string $text): Tag {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT tag_id, nombre FROM tags 
        WHERE (UPPER(nombre)) LIKE UPPER(%% %s %%)',pg_escape_string($text));
        
        if (($rs = pg_query($conn, $query)) === false)
            throw new ModalException(pg_last_error($conn));

        $tag = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $tag = new Tag();
            $tag->setId($row['tag_id']);
            $tag->setNombre($row['nombre']);
        }
        if (($error = pg_last_error($conn)) != false)
            throw new ModalException($error);

        if (!pg_free_result($rs))
            throw new ModalException(pg_last_error());

        return $tag;
    }
}
