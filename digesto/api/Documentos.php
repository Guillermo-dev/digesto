<?php

namespace api;

use Exception;
use helpers\Response;
use models\Documento;
use models\Pdf;

/**
 * Class Documentos
 *
 * @package api
 */
abstract class Documentos {
    /**
     *
     * @throws Exception
     */
    public static function getDocumentos(): void {
        Response::getResponse()->appendData('documentos', Documento::getDocumentos($wPublics = true));
        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getDocumento(int $id = 0): void {
        $documento = Documento::getDocumentoById($id);
        if (!$documento) throw new Exception('El documento no existe', 404);

        Response::getResponse()->appendData('documento', $documento);

        if ($documento->getDescargable())
            Response::getResponse()->appendData('pdf', Pdf::getPdfById($documento->getPdfId()));

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
