<?php

namespace api;

use Exception;
use models\Documento;
use models\Pdf;
use models\Emisor;
use models\Tipo;
use models\Permiso;
use models\Tag;
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

        if (isset($_SESSION['user'])) {
            Response::getResponse()->appendData('documento', $documento);
            Response::getResponse()->appendData('emisor', Emisor::getEmisorById($documento->getEmisorId()));
            Response::getResponse()->appendData('tipo', Tipo::getTipoById($documento->getTipoId()));
            Response::getResponse()->appendData('tags', Tag::getTagsByDocumento($documento));
            Response::getResponse()->appendData('pdf', Pdf::getPdfById($documento->getPdfId()));
        }
        if ($documento->getPublico()) {
            Response::getResponse()->appendData('documento', $documento);
            Response::getResponse()->appendData('emisor', Emisor::getEmisorById($documento->getEmisorId()));
            Response::getResponse()->appendData('tipo', Tipo::getTipoById($documento->getTipoId()));
            Response::getResponse()->appendData('tags', Tag::getTagsByDocumento($documento));
            if ($documento->getDescargable())
                Response::getResponse()->appendData('pdf', Pdf::getPdfById($documento->getPdfId()));
        }
    }

    /**
     *
     * @throws Exception
     */
    public static function createDocumento(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Debe iniciar sesión para realizar esta acción', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_create', $usuarioId))
            throw new ApiException('No tiene permisos necesarios', Response::FORBIDDEN);

        \models\Connection::begin();

        $documento = new Documento();

        if (isset($_POST['numeroExpediente']))
            $documento->setNumeroExpediente($_POST['numeroExpediente']);
        else throw new ApiException('Numero de expediente requerido', Response::BAD_REQUEST);

        if (isset($_POST['titulo']))
            $documento->setTitulo($_POST['titulo']);
        else throw new ApiException('Titulo requerido', Response::BAD_REQUEST);

        if (isset($_POST['descripcion']))
            $documento->setDescripcion($_POST['descripcion']);

        if (isset($_POST['fechaEmision']))
            $documento->setFechaEmision($_POST['fechaEmision']);
        else throw new ApiException('Fecha de emision requerida', Response::BAD_REQUEST);

        if (isset($_POST['descargable']))
            $documento->setDescargable(intval($_POST['descargable']));
        else $documento->setDescargable(true);

        if (isset($_POST['publico']))
            $documento->setPublico(intval($_POST['publico']));
        else $documento->setPublico(true);

        if (isset($_POST['tags'])) {
            $tagsArray = json_decode($_POST['tags']);
            $tagsIds = [];
            if (is_array($tagsArray)) {
                foreach ($tagsArray as $tagName) {
                    $tagName = strtolower($tagName);
                    $tag = Tag::getTagByName($tagName);
                    if ($tag == null) {
                        $tag = new Tag();
                        $tag->setNombre($tagName);
                        Tag::createTag($tag);
                    }
                    $tagsIds[] = $tag->getId();
                }
            }
        } else throw new ApiException('Etiquetas requeridas', Response::BAD_REQUEST);

        if (isset($_POST['emisor']))
            $emisor = Emisor::getEmisorBynombre($_POST['emisor']);
        else throw new ApiException('Emisor requerido', Response::BAD_REQUEST);
        if (!$emisor) {
            $emisor = new Emisor();
            $emisor->setNombre($_POST['emisor']);
            Emisor::createEmisor($emisor);
        }
        $documento->setEmisorId($emisor->getId());

        if (isset($_POST['tipo']))
            $tipo = Tipo::getTipoBynombre($_POST['tipo']);
        else throw new ApiException('Emisor requerido', Response::BAD_REQUEST);
        if (!$tipo) {
            $tipo = new Tipo();
            $tipo->setNombre($_POST['tipo']);
            Tipo::createTipo($tipo);
        }
        $documento->setTipoId($tipo->getId());

        if (!$_FILES['documento_pdf'])
            throw new ApiException('Documento PDF requerido', Response::BAD_REQUEST);

        if ($_FILES['documento_pdf']['error'] > 0)
            throw new ApiException('Hubo un error en la carga del archivo', Response::BAD_REQUEST);

        $namePdf = $_FILES['documento_pdf']['name'];
        $tmpPathPdf = $_FILES['documento_pdf']['tmp_name'];

        $validExt = array('pdf');
        $filesExt = strtolower(pathinfo($namePdf, PATHINFO_EXTENSION));
        if (!in_array($filesExt, $validExt))
            throw new ApiException('El tipo de archivo no es valido', Response::BAD_REQUEST);

        $pdf = new Pdf();
        Pdf::createPdf($pdf);

        // Update y creacion del path
        $pathPdf = 'uploads/' . strtolower($pdf->getId() . $namePdf);
        $pathPdf = str_replace(' ', '', $pathPdf);
        $pdf->setPath($pathPdf);
        Pdf::updatePdf($pdf);


        // Guardado de pdf
        if (!move_uploaded_file($tmpPathPdf, $pathPdf))
            throw new ApiException('No se pudo guardar el archivo', Response::INTERNAL_SERVER_ERROR);

        $documento->setPdfId($pdf->getId());
        $documento->setUsuarioId($usuarioId);

        Documento::createDocumento($documento);
        foreach ($tagsIds as $tagId) {
            Documento::assignTagDocumento($documento->getId(), $tagId);
        }

        \models\Connection::commit();
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateDocumento(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Debe iniciar sesión para realizar esta acción', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_update', $usuarioId))
            throw new ApiException('No tiene permisos necesarios', Response::FORBIDDEN);

        $documento = Documento::getDocumentoById($id);

        if (isset($_POST['numeroExpediente']))
            $documento->setNumeroExpediente($_POST['numeroExpediente']);

        if (isset($_POST['titulo']))
            $documento->setTitulo($_POST['titulo']);

        if (isset($_POST['descripcion']))
            $documento->setDescripcion($_POST['descripcion']);

        if (isset($_POST['fechaEmision']))
            $documento->setFechaEmision($_POST['fechaEmision']);

        if (isset($_POST['descargable']))
            $documento->setDescargable($_POST['descargable'] === 'false' ? false : true);

        if (isset($_POST['publico']))
            $documento->setPublico($_POST['publico'] === 'false' ? false : true);

        if (isset($_POST['derogado'])) {
            if ($_POST['derogado']) {
                if (isset($_POST['derogadoId'])) {
                    $documentoDerogado = Documento::getDocumentoById($_POST['derogadoId']);
                    if ($documentoDerogado != null) {
                        $documento->setDerogado(true);
                        $documento->setDerogadoId($documentoDerogado->getId());
                    } else
                        throw new ApiException('Documento derogado necesario', Response::BAD_REQUEST);
                }
            } else {
                $documento->setDerogado(false);
                $documento->setDerogadoId(null);
            }
        } else {
            $documento->setDerogado(false);
            $documento->setDerogadoId(null);
        }

        if (isset($_POST['tags'])) {
            Documento::clearTagDocumento($id);
            $tagsArray = json_decode($_POST['tags']);
            $tagsIds = [];
            if (is_array($tagsArray)) {
                foreach ($tagsArray as $tagName) {
                    $tagName = strtolower($tagName);
                    $tag = Tag::getTagByName($tagName);
                    if ($tag == null) {
                        $tag = new Tag();
                        $tag->setNombre($tagName);
                        Tag::createTag($tag);
                    }
                    $tagsIds[] = $tag->getId();
                }
            }
        }

        if (isset($_POST['emisor'])) {
            $emisor = Emisor::getEmisorBynombre($_POST['emisor']);
            if (!$emisor) {
                $emisor = new Emisor();
                $emisor->setNombre($_POST['emisor']);
                Emisor::createEmisor($emisor);
            }
            $documento->setEmisorId($emisor->getId());
        }

        if (isset($_POST['tipo'])) {
            $tipo = Tipo::getTipoBynombre($_POST['tipo']);
            if (!$tipo) {
                $tipo = new Tipo();
                $tipo->setNombre($_POST['tipo']);
                Tipo::createTipo($tipo);
            }
            $documento->setTipoId($tipo->getId());
        }

        if (isset($_FILES['documento_pdf'])) {
            if ($_FILES['documento_pdf']['error'] > 0)
                throw new ApiException('Hubo un error en la carga del archivo', Response::BAD_REQUEST);

            $namePdf = $_FILES['documento_pdf']['name'];
            $tmpPathPdf = $_FILES['documento_pdf']['tmp_name'];

            $validExt = array('pdf');
            $filesExt = strtolower(pathinfo($namePdf, PATHINFO_EXTENSION));
            if (!in_array($filesExt, $validExt))
                throw new ApiException('El tipo de archivo no es valido', Response::BAD_REQUEST);

            $pdf = new Pdf();
            Pdf::createPdf($pdf);

            // Update y creacion del path
            $pathPdf = 'uploads/' . strtolower($pdf->getId() . $namePdf);
            $pathPdf = str_replace(' ', '', $pathPdf);
            $pdf->setPath($pathPdf);
            Pdf::updatePdf($pdf);

            // Guardado de pdf
            if (!move_uploaded_file($tmpPathPdf, $pathPdf))
                throw new ApiException('No se pudo guardar el archivo', Response::INTERNAL_SERVER_ERROR);

            $documento->setPdfId($pdf->getId());
        }

        if (isset($tagsIds)) {
            foreach ($tagsIds as $tagId) {
                Documento::assignTagDocumento($documento->getId(), $tagId);
            }
        }

        $documento->setUsuarioId($usuarioId);
        Documento::updateDocumento($documento);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteDocumento(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Debe iniciar sesión para realizar esta acción', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('documentos_delete', $usuarioId))
            throw new ApiException('No tiene permisos necesarios', Response::FORBIDDEN);

        $documento = Documento::getDocumentoById($id);
        if (!$documento)
            throw new ApiException('El documento no existe', Response::NOT_FOUND);

        Documento::deleteDocumento($documento->getID());
        Documento::clearTagDocumento($documento->getID());
    }
}
