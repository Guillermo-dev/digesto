<?php

namespace api;

use Exception;
use helpers\Request;
use helpers\Response;
use models\Documento;
use models\Pdf;
use models\Emisor;
use models\Permiso;
use models\Tag;

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
    public static function getPublicsDocumentos(): void {
        if (isset($_GET['search']) or isset($_GET['emisores']) or isset($_GET['etiquetas']) or isset($_GET['anios'])) {
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $emisores = isset($_GET['emisores']) ? $_GET['emisores'] : '';
            $etiquetas = isset($_GET['etiquetas']) ? $_GET['etiquetas'] : '';
            $anios = isset($_GET['anios']) ? $_GET['anios'] : '';
            Response::getResponse()->appendData('documentos', Documento::getDocumentosSearch($search, $emisores, $etiquetas, $anios, $onlyPublics = true));
        } else
            Response::getResponse()->appendData('documentos', Documento::getDocumentos($onlyPublics = true));

        Response::getResponse()->setStatus('success');
    }

    public static function getAllDocumentos(): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Unauthorized', 401);

        if (isset($_GET['search']) or isset($_GET['emisores']) or isset($_GET['etiquetas']) or isset($_GET['anios'])) {
            $search = isset($_GET['search']) ? $_GET['search'] : '';
            $emisores = isset($_GET['emisores']) ? $_GET['emisores'] : '';
            $etiquetas = isset($_GET['etiquetas']) ? $_GET['etiquetas'] : '';
            $anios = isset($_GET['anios']) ? $_GET['anios'] : '';
            Response::getResponse()->appendData('documentos', Documento::getDocumentosSearch($search, $emisores, $etiquetas, $anios, $onlyPublics = false));
        } else
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

        if (!$documento->getPublico())
            if (!isset($_SESSION['user']))
                throw new Exception('Unauthorized', 401);

        Response::getResponse()->appendData('documento', $documento);

        Response::getResponse()->appendData('emisor', Emisor::getEmisorById($documento->getEmisorId()));

        Response::getResponse()->appendData('tags', Tag::getTagsByDocumento($documento));

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

        $documento = Documento::getDocumentoById($id);
        if (!$documento) throw new Exception('El documento no existe', 404);

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

        if (isset($documentoData->usuarioId))
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
