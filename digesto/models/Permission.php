<?php

namespace models;

use Exception;
use JsonSerializable;

/**
 * Class Permission
 *
 * @package models
 */
class Permission implements JsonSerializable {
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $description;

    /**
     * Permission constructor.
     *
     * @param int    $id
     * @param string $name
     * @param string $description
     */
    public function __construct(int $id = 0, string $name = '', string $description = '') {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
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
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @param int $id
     *
     * @return Permission
     */
    public function setId(int $id): Permission {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     *
     * @return Permission
     */
    public function setName(string $name): Permission {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $description
     *
     * @return Permission
     */
    public function setDescription(string $description): Permission {
        $this->description = $description;
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
    public static function getPermissions(): array {
        $conn = Connection::getConnection();

        $query = 'SELECT id, name, description FROM permissions';
        if (($rs = pg_query($conn, $query)) === false)
            throw new Exception(pg_last_error($conn));

        $permissions = [];
        while (($row = pg_fetch_assoc($rs)) != false) {
            $permission = new Permission();
            $permission->setId($row['id']);
            $permission->setName($row['name']);
            $permission->setDescription($row['description']);
            $permissions[] = $permission;
        }

        if (($error = pg_last_error($conn)) != false)
            throw new Exception($error);

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error($conn));

        return $permissions;
    }

    /**
     * @param int $id
     *
     * @return Permission|null
     * @throws Exception
     */
    public static function getPermissionById(int $id): ?Permission {
        $conn = Connection::getConnection();

        $query = sprintf('SELECT id, name, description FROM permissions WHERE id=%d', $id);
        if (($rs = pg_query($conn, $query)) === false)
            throw new Exception(pg_last_error($conn));

        $permission = null;
        if (($row = pg_fetch_assoc($rs)) != false) {
            $permission = new Permission();
            $permission->setId($row['id']);
            $permission->setName($row['name']);
            $permission->setDescription($row['description']);
        }

        if (($error = pg_last_error($conn)) != false)
            throw new Exception($error);

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error());

        return $permission;
    }

    /**
     * @param Permission $permission
     *
     * @throws Exception
     */
    public static function createPermission(Permission $permission): void {
        $conn = Connection::getConnection();

        $query = sprintf("INSERT INTO permissions (name, description) VALUES ('%s','%s') RETURNING Currval('permissions_id_seq')",
            pg_escape_string($permission->getName()),
            pg_escape_string($permission->getDescription())
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new Exception(pg_last_error($conn));

        if (($row = pg_fetch_row($rs)))
            $permission->setId(($row[0]));
        else throw new Exception(pg_last_error($rs));

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error($conn));
    }

    /**
     * @param Permission $permission
     *
     * @throws Exception
     */
    public static function updatePermission(Permission $permission): void {
        $conn = Connection::getConnection();

        $query = sprintf("UPDATE permissions SET name='%s', description='%s' WHERE id=%d",
            pg_escape_string($permission->getName()),
            pg_escape_string($permission->getDescription()),
            $permission->getId()
        );

        if (($rs = pg_query($conn, $query)) === false)
            throw new Exception(pg_errormessage($conn));

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error($conn));
    }

    /**
     * @param Permission $permission
     *
     * @throws Exception
     */
    public static function deletePermission(Permission $permission): void {
        $conn = Connection::getConnection();

        $query = sprintf("DELETE FROM permissions WHERE id=%d", $permission->getId());

        if (!($rs = pg_query($conn, $query)))
            throw new Exception(pg_last_error($conn));

        if (!pg_free_result($rs))
            throw new Exception(pg_last_error($conn));
    }
}