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

        if (isset($_SESSION['user']) && isset($_GET['visible']))
            $onlyPublic = false;

        if ($filtered)
            Response::getResponse()->appendData('documentos', Documento::getDocumentosSearch($_GET['search'], $_GET['emisores'], $_GET['etiquetas'], $_GET['anios'], $onlyPublic));
        else
            Response::getResponse()->appendData('documentos', Documento::getDocumentos($onlyPublic));
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getDocumento(int $id = 0): void {
        $documento = Documento::getDocumentoById($id);
        if (!$documento)
            throw new ApiException('El documento no existe', Response::NOT_FOUND);

        if (!$documento->getPublico())
            if (!isset($_SESSION['user']))
                throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

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
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_create', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $documento = new Documento();

        if (isset($_GET['numeroExpediente']))
            $documento->setNumeroExpediente($_GET['numeroExpediente']);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        if (isset($_GET['titulo']))
            $documento->setTitulo($_GET['titulo']);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        if (isset($_GET['descripcion']))
            $documento->setDescripcion($_GET['descripcion']);

        if (isset($_GET['tipo']))
            $documento->setTipo($_GET['tipo']);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        if (isset($_GET['fechaEmisio']))
            $documento->setFechaEmision($_GET['fechaEmisio']);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        if (isset($_GET['descargable']))
            $documento->setDescargable($_GET['descargable']);
        else $documento->setDescargable(true);

        if (isset($_GET['publico']))
            $documento->setPublico($_GET['publico']);
        else $documento->setPublico(true);

        if (isset($_GET['emisorID']))
            $documento->setEmisorId($_GET['emisorID']);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        if ($_FILES['documento_pdf']) {
            $namePdf = $_FILES['documento_pdf']['name'];
            $tmpPathPdf = $_FILES['documento_pdf']['tmp_name'];
            $error = $_FILES['fichero_usuario']['error'];

            if ($error > 0)
                throw new ApiException('Bad Request', Response::BAD_REQUEST);

            $filesExt = strtolower(pathinfo($namePdf, PATHINFO_EXTENSION));
            $validExt = array('pdf');
            if (!in_array($filesExt, $validExt))
                throw new ApiException('Bad Request', Response::BAD_REQUEST);

            $tmpPdfBd = new Pdf();
            $pdfId = Pdf::createPdf($tmpPdfBd);

            // Update y creacion del path
            $namePdf =  'uploads/' . strtolower($namePdf . strval($pdfId));
            $namePdf = preg_replace('/\s+/', '-', $namePdf);
            $tmpPdfBd->setPath($namePdf);
            Pdf::updatePdf($tmpPdfBd);

            // Guardacion del pdf
            if (!move_uploaded_file($tmpPathPdf, $namePdf))
                throw new ApiException('Bad Request', Response::BAD_REQUEST);

            $documento->setPdfId($pdfId);

            $documento->setUsuarioId($usuarioId);

            Documento::createDocumento($documento);
        }
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateDocumento(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_update', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $documentoData = Request::getBodyAsJson();

        $documento = Documento::getDocumentoById($id);
        if (!$documento)
            throw new ApiException('El documento no existe', Response::NOT_FOUND);

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
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_delete', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $documento = Documento::getDocumentoById($id);
        if (!$documento)
            throw new ApiException('El documento no existe', Response::NOT_FOUND);

        Documento::deleteDocumento($documento->getID());
    }
}
