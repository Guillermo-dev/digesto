<?php

namespace api\util;

use JsonException;
use stdClass;

/**
 * Class Request
 *
 * @package api\util
 */
class Request {
    /**
     * Request constructor.
     */
    private function __construct() { }

    /**
     * @return stdClass
     * @throws JsonException
     */
    public static function getBodyAsJson(): stdClass {
        return json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
    }
}