<?php

namespace api;

use Exception;
use helpers\Request;
use helpers\Response;
use models\Documento;
use models\Pdf;
use models\Emisor;
use models\Permiso;

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
        if (!isset($_SESSION['user']))
            Response::getResponse()->appendData('documentos', Documento::getDocumentos($onlyPublics = true));
        else
            Response::getResponse()->appendData('documentos', Documento::getDocumentos($onlyPublics = false));
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

        Response::getResponse()->appendData('emisor', Emisor::getEmisorById($documento->getEmisorId()));

        if ($documento->getDescargable())
            Response::getResponse()->appendData('pdf', Pdf::getPdfById($documento->getPdfId()));

        Response::getResponse()->setStatus('success');
    }

    /**
     *
     * @throws Exception
     */
    public static function createDocumento(): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Unauthorized', 401);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_create', $usuarioId))
            throw new Exception('Forbidden', 403);

        $documentoData = Request::getBodyAsJson();

        // TODO: Validacion de datos
        if (!isset($documentoData->numeroExpediente))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->titulo))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->descripcion))
            $documentoData->descripcion = '';
        if (!isset($documentoData->tipo))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->fechaEmisio))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->descargable))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->publico))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->pdfId))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->emisorID))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->usuarioId))
            throw new Exception('Bad Request', 400);

        $documento = new Documento();
        $documento->setNumeroExpediente($documentoData->numeroExpediente);
        $documento->setTitulo($documentoData->titulo);
        $documento->setDescripcion($documentoData->descripcion);
        $documento->setTipo($documentoData->tipo);
        $documento->setFechaEmision($documentoData->fechaEmisio);
        $documento->setDescargable($documentoData->descargable);
        $documento->setPublico($documentoData->publico);
        $documento->setPdfId($documentoData->pdfId);
        $documento->setEmisorId($documentoData->emisorID);
        $documento->setUsuarioId($documentoData->usuarioId);

        Documento::createDocumento($documento);

        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateDocumento(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Unauthorized', 401);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_update', $usuarioId))
            throw new Exception('Forbidden', 403);

        $documentoData = Request::getBodyAsJson();
        // TODO: Validacion de datos

        if (!isset($documentoData->numeroExpediente))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->titulo))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->descripcion))
            $documentoData->descripcion = '';
        if (!isset($documentoData->tipo))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->fechaEmisio))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->descargable))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->publico))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->pdfId))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->emisorID))
            throw new Exception('Bad Request', 400);
        if (!isset($documentoData->usuarioId))
            throw new Exception('Bad Request', 400);

        $documento = Documento::getDocumentoById($id);
        if (!$documento) throw new Exception('El documento no existe', 404);

        $documento->setNumeroExpediente($documentoData->numeroExpediente);
        $documento->setTitulo($documentoData->titulo);
        $documento->setDescripcion($documentoData->descripcion);
        $documento->setTipo($documentoData->tipo);
        $documento->setFechaEmision($documentoData->fechaEmisio);
        $documento->setDescargable($documentoData->descargable);
        $documento->setPublico($documentoData->publico);
        $documento->setPdfId($documentoData->pdfId);
        $documento->setEmisorId($documentoData->emisorID);
        $documento->setUsuarioId($documentoData->usuarioId);

        Documento::updateDocumento($documento);

        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteDocumento(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Unauthorized', 401);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_delete', $usuarioId))
            throw new Exception('Forbidden', 403);

        $documento = Documento::getDocumentoById($id);
        if (!$documento) throw new Exception('El documento no existe', 404);

        Documento::deleteDocumento($documento->getID());

        Response::getResponse()->setStatus('success');
    }
}
