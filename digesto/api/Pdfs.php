<?php

namespace api;

use Exception;
use helpers\Response;
use models\Pdf;

/**
 * Class Pdfs
 *
 * @package api
 */
abstract class Pdfs {

    /**
     * @throws Exception
     */
    public static function getPdfs(): void {
        Response::getResponse()->appendData('pdfs', Pdf::getPdfs());
        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getPdf(int $id = 0): void {
        Response::getResponse()->appendData('pdf', Pdf::getPdfById($id));
        Response::getResponse()->setStatus('success');
    }
}
