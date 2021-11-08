<?php

namespace api\util;

use JsonException;
use JsonSerializable;

/**
 * Class Response
 *
 * @package api\util
 */
class Response implements JsonSerializable {
    const OK = 200;
    const CREATED = 201;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const CONFLICT = 409;
    const INTERNAL_SERVER_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const SERVICE_UNAVAILABLE = 503;

    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $status;

    /**
     * @var int
     */
    private $timestamp;

    /**
     * @var array|null
     */
    private $data;

    /**
     * @var ResponseError|null
     */
    private $error;

    /**
     * @var Response
     */
    private static $response = null;

    /**
     * @var string[]
     */
    private static $codeStatus = [
        200 => "OK",
        201 => "Created",
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        409 => "Conflict",
        500 => "Internal Server Error",
        501 => "Not implemented",
        503 => "Service Unavailable",
    ];

    /**
     * @var string[]
     */
    private static $codeDetail = [
        200 => "The request was successfully completed",
        201 => "A new resource was successfully created",
        400 => "The request was invalid",
        401 => "The request did not include an authentication token or the authentication token was expired",
        403 => "The client did not have permission to access the requested resource",
        404 => "The requested resource was not found",
        405 => "The HTTP method in the request was not supported by the resource",
        409 => "The request could not be completed due to a conflict",
        500 => "The request was not completed due to an internal error on the server side",
        501 => "Not implemented",
        503 => "The server was unavailable",
    ];

    /**
     * Response constructor.
     */
    private function __construct() {
        $this->code = self::OK;
        $this->status = self::$codeStatus[self::OK];
        $this->timestamp = time();
        $this->data = null;
        $this->error = null;
    }

    /**
     * @return int
     */
    public function getCode(): int {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return int
     */
    public function getTimestamp(): int {
        return $this->timestamp;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array {
        return $this->data;
    }

    /**
     * @return ResponseError|null
     */
    public function getError(): ?ResponseError {
        return $this->error;
    }

    /**
     * @param int $code
     *
     * @return string
     */
    public static function getCodeStatus(int $code): string {
        return self::$codeStatus[$code] ?? '';
    }

    /**
     * @param int $code
     *
     * @return string
     */
    public static function getCodeDetail(int $code): string {
        return self::$codeDetail[$code] ?? '';
    }

    /**
     * @param int $code
     *
     * @return Response
     */
    public function setCode(int $code): Response {
        $this->code = $code;

        if (isset(self::$codeStatus[$code]))
            $this->status = self::$codeStatus[$code];
        else $this->status = 'Unknown Status';

        return $this;
    }

    /**
     * @param string $status
     *
     * @return Response
     */
    public function setStatus(string $status): Response {
        $this->status = $status;
        return $this;
    }

    /**
     * @param array|null $data
     *
     * @return Response
     */
    public function setData(?array $data): Response {
        $this->data = $data;
        return $this;
    }

    /**
     * @param string $message
     * @param int    $code
     *
     * @return Response
     */
    public function setError(string $message, int $code): Response {
        $this->error = new ResponseError($code, $message);
        return $this;
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return Response
     */
    public function appendData(string $key, $value): Response {
        if ($this->data === null)
            $this->data = [];
        $this->data[$key] = $value;
        return self::$response;
    }

    /**
     * @return void
     */
    public function send(): void {
        header('Content-type: application/json', true, $this->code);
        try {
            echo json_encode(get_object_vars($this), JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->setCode(500);
            $this->setData(null);
            $this->setError($e->getMessage(), $e->getCode());
            echo json_encode(get_object_vars($this));
        }
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    /**
     * @return Response
     */
    public static function getResponse(): Response {
        if (!self::$response)
            self::$response = new Response();
        return self::$response;
    }
}