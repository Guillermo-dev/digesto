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
        $publicos = 'TRUE';
        $privados = 'TRUE';

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


        if (isset($_SESSION['user']) && isset($_GET['visible'])) {
            $publicos = 'FALSE';
            $privados = 'TRUE';
            $onlyPublic = false;
        }


        if (isset($_GET['privacidad'])) {
            if ($_GET['privacidad'] == 'publicos')
                $publicos = 'TRUE';
            else
                $publicos = 'FALSE';
            if ($_GET['privacidad'] == 'privados')
                $privados = 'FALSE';
            else
                $privados = 'TRUE';
            $filtered = true;
        }
        
        if ($filtered)
            Response::getResponse()->appendData('documentos', Documento::getDocumentosSearch($_GET['search'], $_GET['emisores'], $_GET['etiquetas'], $_GET['anios'], $onlyPublic, $publicos, $privados));
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

        if (isset($_GET['fechaEmision']))
            $documento->setFechaEmision($_GET['fechaEmision']);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        if (isset($_GET['descargable']))
            $documento->setDescargable($_GET['descargable']);
        else $documento->setDescargable(true);

        if (isset($_GET['publico']))
            $documento->setPublico($_GET['publico']);
        else $documento->setPublico(true);

        if (isset($_GET['tags'])) {
            $tagsIds = [];
            foreach ($_GET['tags'] as $tagName) {
                $tagName = strtolower($tagName);
                if (!$tag = Tag::getTagByName($tagName)) {
                    $tag = new Tag();
                    $tag->setNombre($tagName);
                    Tag::createTag($tag);
                }
                $tagsIds[] = $tag->getId();
            }
        } else throw new ApiException('Bad Request', Response::BAD_REQUEST);


        if (isset($_GET['emisor']))
            $emisor = Emisor::getEmisorBynombre($_GET['emisor']);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);
        if (!$emisor)
            throw new ApiException('Bad Request', Response::BAD_REQUEST);
        $documento->setEmisorId($emisor->getId());


        if (!$_FILES['documento_pdf'])
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        if ($_FILES['fichero_usuario']['error'] > 0)
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        $namePdf = $_FILES['documento_pdf']['name'];
        $tmpPathPdf = $_FILES['documento_pdf']['tmp_name'];

        $validExt = array('pdf');
        $filesExt = strtolower(pathinfo($namePdf, PATHINFO_EXTENSION));
        if (!in_array($filesExt, $validExt))
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        $pdf = new Pdf();
        Pdf::createPdf($pdf);

        // Update y creacion del path
        $pathPdf =  'uploads/' . strtolower($namePdf . strval($pdf->getId()) . '.' . $filesExt);
        $pathPdf = preg_replace('/\s+/', '-', $pathPdf);
        $pdf->setPath($pathPdf);
        Pdf::updatePdf($pdf);

        // Guardado de pdf
        if (!move_uploaded_file($tmpPathPdf, $namePdf))
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        $documento->setPdfId($pdf->getId());
        $documento->setUsuarioId($usuarioId);

        Documento::createDocumento($documento);
        foreach ($tagsIds as $tagId) {
            Documento::assignTagDocumento($documento->getId(), $tagId);
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

        $documento = Documento::getDocumentoById($id);

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

        if (isset($_GET['fechaEmision']))
            $documento->setFechaEmision($_GET['fechaEmision']);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        if (isset($_GET['descargable']))
            $documento->setDescargable($_GET['descargable']);
        else $documento->setDescargable(true);

        if (isset($_GET['publico']))
            $documento->setPublico($_GET['publico']);
        else $documento->setPublico(true);


        if (isset($_GET['tags'])) {
            $tagsIds = [];
            foreach ($_GET['tags'] as $tagName) {
                $tagName = strtolower($tagName);
                if (!$tag = Tag::getTagByName($tagName)) {
                    $tag = new Tag();
                    $tag->setNombre($tagName);
                    Tag::createTag($tag);
                }
                $tagsIds[] = $tag->getId();
            }
        } else throw new ApiException('Bad Request', Response::BAD_REQUEST);


        if (isset($_GET['emisor']))
            $emisor = Emisor::getEmisorBynombre($_GET['emisor']);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);
        if (!$emisor)
            throw new ApiException('Bad Request', Response::BAD_REQUEST);
        $documento->setEmisorId($emisor->getId());

        if (!$_FILES['documento_pdf'])
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        if ($_FILES['fichero_usuario']['error'] > 0)
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        $namePdf = $_FILES['documento_pdf']['name'];
        $tmpPathPdf = $_FILES['documento_pdf']['tmp_name'];

        $validExt = array('pdf');
        $filesExt = strtolower(pathinfo($namePdf, PATHINFO_EXTENSION));
        if (!in_array($filesExt, $validExt))
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        $pdf = new Pdf();
        Pdf::createPdf($pdf);

        // Update y creacion del path
        $pathPdf =  'uploads/' . strtolower($namePdf . strval($pdf->getId()) . '.' . $filesExt);
        $pathPdf = preg_replace('/\s+/', '-', $pathPdf);
        $pdf->setPath($pathPdf);
        Pdf::updatePdf($pdf);

        // Guardado de pdf
        if (!move_uploaded_file($tmpPathPdf, $namePdf))
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        $documento->setPdfId($pdf->getId());
        $documento->setUsuarioId($usuarioId);

        Documento::updateDocumento($documento);
        foreach ($tagsIds as $tagId) {
            Documento::assignTagDocumento($documento->getId(), $tagId);
        }
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
