<?php

namespace api;

use helpers\Response;

/**
 * Class NotFound
 *
 * @package api
 */
abstract class NotFound {
    /**
     *
     */
    public static function index(): void {
        Response::getResponse()->setStatus('error');
        Response::getResponse()->setError(404, 'Not found');
    }
}
