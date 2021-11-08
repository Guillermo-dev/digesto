<?php

namespace models;

use models\exceptions\ModalException;

/**
 * Class Connection
 *
 * @package models
 */
abstract class Connection {
    /**
     * @var resource
     */
    private static $conn = null;

    /**
     * Connection constructor.
     */
    private function __construct() {
    }

    /**
     * @return string
     */
    private static function getConnectionString(): string {
        $arr = explode(';', file_get_contents('config/db-config'));
        return sprintf("host=%s user=%s password=%s dbname=%s", $arr[0], $arr[1], $arr[2], $arr[3]);
    }

    /**
     * @return resource
     * @throws ModalException
     */
    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = pg_connect(self::getConnectionString());
            if (self::$conn === false)
                throw new ModalException('Cannot connect to the database');
            pg_set_error_verbosity(self::$conn, PGSQL_ERRORS_VERBOSE);
        }
        return self::$conn;
    }

    /**
     * @return string
     */
    public static function getErrorCode(): string {
        if (!self::$conn) return '';
        $matches = [];
        preg_match('/ERROR: +(\d+)/', pg_last_error(self::$conn), $matches);
        return $matches[1];
    }
}
