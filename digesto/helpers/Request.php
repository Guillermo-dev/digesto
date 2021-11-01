<?php

namespace helpers;

use JsonException;
use stdClass;

/**
 * Class Request
 *
 * @package helpers
 */
abstract class Request {
    /**
     * @return stdClass
     * @throws JsonException
     */
    public static function getBodyAsJson(): stdClass {
        return json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
    }
}