<?php

namespace api;

use Exception;
use helpers\Response;
use models\Documento;

/**
 * Class Documentos
 *
 * @package api
 */
abstract class Documentos {
    /**
     *
     */
    public static function getDocumentos(): void {
        Response::getResponse()->appendData('documentos', Documento::getDocumentos());
        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     */
    public static function getDocumento(int $id = 0): void {
        Response::getResponse()->appendData('documento', Documento::getDocumentoById($id));
        Response::getResponse()->setStatus('success');
    }

    /**
     *
     * @throws Exception
     */
    public static function createDocumento(): void {
        throw new Exception('Not implemented', 504);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateDocumento(int $id = 0): void {
        throw new Exception('Not implemented', 504);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteDocumento(int $id = 0): void {
        throw new Exception('Not implemented', 504);
    }
}
