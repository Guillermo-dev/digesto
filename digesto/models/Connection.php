<?php

namespace models;

use Exception;

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
     * @throws Exception
     */
    public static function getConnection() {
        if (self::$conn === null) {
            self::$conn = pg_connect(self::getConnectionString());
            if (self::$conn === false)
                throw new Exception('Cannot connect to the database');
        }
        return self::$conn;
    }
}
