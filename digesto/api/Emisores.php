<?php

namespace api;

use Exception;
use helpers\Response;
use models\Emisor;

/**
 * Class Emisores
 *
 * @package api
 */
abstract class Emisores {

    /**
     * @throws Exception
     */
    public static function getEmisores(): void {
        Response::getResponse()->appendData('emisores', Emisor::getEmisores());
        Response::getResponse()->setStatus('success');
    }

    /**
     * @throws Exception
     */
    public static function getEmisor(int $id = 0): void {
        Response::getResponse()->appendData('emisor', Emisor::getEmisorById($id));
        Response::getResponse()->setStatus('success');
    }


    /**
     * @throws Exception
     */
    public static function createEmisor(): void {
        throw new Exception('Not implemented', 504);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateEmisor(int $id = 0): void {
        throw new Exception('Not implemented', 504);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteEmisor(int $id = 0): void {
        throw new Exception('Not implemented', 504);
    }
}
