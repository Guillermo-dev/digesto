<?php

namespace api;

use Exception;
use models\Documento;
use models\Pdf;
use models\Emisor;
use models\Permiso;
use models\Tag;
use api\util\Request;
use api\util\Response;
use api\exceptions\ApiException;

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
        $filtered = false;
        $onlyPublic = true;

        if (!isset($_GET['search'])) {
            $_GET['search'] = '';
            $filtered = true;
        }

        if (!isset($_GET['etiquetas'])) {
            $_GET['etiquetas'] = '';
            $filtered = true;
        }

        if (!isset($_GET['anios'])) {
            $_GET['anios'] = '';
            $filtered = true;
        }

        if (!isset($_GET['emisores'])) {
            $_GET['emisores'] = '';
            $filtered = true;
        }

        if (isset($_SESSION['user']))
            $onlyPublic = false;

        if ($filtered) Response::getResponse()->appendData('documentos', Documento::getDocumentosSearch($_GET['search'], $_GET['emisores'], $_GET['etiquetas'], $_GET['anios'], $onlyPublic));
        else Response::getResponse()->appendData('documentos', Documento::getDocumentos($onlyPublic));
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getDocumento(int $id = 0): void {
        $documento = Documento::getDocumentoById($id);
        if (!$documento)
            throw new ApiException('El documento no existe', Response::RESPONSE_NOT_FOUND);

        if (!$documento->getPublico())
            if (!isset($_SESSION['user']))
                throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        Response::getResponse()->appendData('documento', $documento);
        Response::getResponse()->appendData('emisor', Emisor::getEmisorById($documento->getEmisorId()));
        Response::getResponse()->appendData('tags', Tag::getTagsByDocumento($documento));

        if ($documento->getDescargable())
            Response::getResponse()->appendData('pdf', Pdf::getPdfById($documento->getPdfId()));
    }

    /**
     *
     * @throws Exception
     */
    public static function createDocumento(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_create', $usuarioId))
            throw new ApiException('Forbidden', Response::RESPONSE_FORBIDDEN);

        $documentoData = Request::getBodyAsJson();

        $documento = new Documento();

        if (isset($documentoData->numeroExpediente))
            $documento->setNumeroExpediente($documentoData->numeroExpediente);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        if (isset($documentoData->titulo))
            $documento->setTitulo($documentoData->titulo);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        if (isset($documentoData->descripcion))
            $documento->setDescripcion($documentoData->descripcion);

        if (isset($documentoData->tipo))
            $documento->setTipo($documentoData->tipo);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        if (isset($documentoData->fechaEmisio))
            $documento->setFechaEmision($documentoData->fechaEmisio);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        if (isset($documentoData->descargable))
            $documento->setDescargable($documentoData->descargable);
        else $documento->setDescargable(true);

        if (isset($documentoData->publico))
            $documento->setPublico($documentoData->publico);
        else $documento->setPublico(true);

        if (isset($documentoData->pdfId))
            $documento->setPdfId($documentoData->pdfId);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        if (isset($documentoData->emisorID))
            $documento->setEmisorId($documentoData->emisorID);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        $documento->setUsuarioId($usuarioId);

        Documento::createDocumento($documento);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateDocumento(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_update', $usuarioId))
            throw new ApiException('Forbidden', Response::RESPONSE_FORBIDDEN);

        $documentoData = Request::getBodyAsJson();

        $documento = Documento::getDocumentoById($id);
        if (!$documento)
            throw new ApiException('El documento no existe', Response::RESPONSE_NOT_FOUND);

        if (isset($documentoData->numeroExpediente))
            $documento->setNumeroExpediente($documentoData->numeroExpediente);

        if (isset($documentoData->titulo))
            $documento->setTitulo($documentoData->titulo);

        if (isset($documentoData->descripcion))
            $documento->setDescripcion($documentoData->descripcion);

        if (isset($documentoData->tipo))
            $documento->setTipo($documentoData->tipo);

        if (isset($documentoData->fechaEmisio))
            $documento->setFechaEmision($documentoData->fechaEmisio);

        if (isset($documentoData->descargable))
            $documento->setDescargable($documentoData->descargable);

        if (isset($documentoData->publico))
            $documento->setPublico($documentoData->publico);

        if (isset($documentoData->pdfId))
            $documento->setPdfId($documentoData->pdfId);

        if (isset($documentoData->emisorID))
            $documento->setEmisorId($documentoData->emisorID);

        Documento::updateDocumento($documento);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteDocumento(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_delete', $usuarioId))
            throw new ApiException('Forbidden', Response::RESPONSE_FORBIDDEN);

        $documento = Documento::getDocumentoById($id);
        if (!$documento)
            throw new ApiException('El documento no existe', Response::RESPONSE_NOT_FOUND);

        Documento::deleteDocumento($documento->getID());
    }
}
