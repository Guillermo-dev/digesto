<?php

namespace api;

use Exception;
use models\Pdf;
use api\util\Response;

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
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getPdf(int $id = 0): void {
        Response::getResponse()->appendData('pdf', Pdf::getPdfById($id));
    }
}
