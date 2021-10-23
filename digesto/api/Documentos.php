<?php

namespace api;

use Exception;
use helpers\Response;

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
        Response::getResponse()->appendData('documentos', [[
            "id" => 52,
            "titulo" => "Hello world!",
            "numero" => "4595/2020",
            "descripcion" => "Esto es la descripcion del documento",
            "pdf" => "",
            "fecha" => date("Y-m-d"),
        ], [
            "id" => 52,
            "titulo" => "Hello world!",
            "numero" => "4595/2020",
            "descripcion" => "Esto es la descripcion del documento",
            "pdf" => "",
            "fecha" => date("Y-m-d"),
        ], [
            "id" => 52,
            "titulo" => "Hello world!",
            "numero" => "4595/2020",
            "descripcion" => "Esto es la descripcion del documento",
            "pdf" => "",
            "fecha" => date("Y-m-d"),
        ], [
            "id" => 52,
            "titulo" => "Hello world!",
            "numero" => "4595/2020",
            "descripcion" => "Esto es la descripcion del documento",
            "pdf" => "",
            "fecha" => date("Y-m-d"),
        ], [
            "id" => 52,
            "titulo" => "Hello world!",
            "numero" => "4595/2020",
            "descripcion" => "Esto es la descripcion del documento",
            "pdf" => "",
            "fecha" => date("Y-m-d"),
        ]]);
        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     */
    public static function getDocumento(int $id = 0): void {
        Response::getResponse()->appendData('documento', [
            "id" => $id,
            "titulo" => "Hello world!",
            "numero" => "4595/2020",
            "descripcion" => "Esto es la descripcion del documento",
            "pdf" => "",
            "fecha" => date("Y-m-d"),
        ]);
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