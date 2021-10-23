<?php

namespace helpers;

use JsonSerializable;
use stdClass;

/**
 * Class Response
 *
 * @package helpers
 */
class Response implements JsonSerializable {
    /**
     * @var Response|null
     */
    private static $response = null;
    /**
     * @var string
     */
    private $status = '';
    /**
     * @var array|null
     */
    private $data = null;
    /**
     * @var stdClass|null
     */
    private $error = null;

    /**
     * Response constructor.
     */
    private function __construct() { }

    /**
     * @return string
     */
    public function getStatus(): string {
        return $this->status;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array {
        return $this->data;
    }

    /**
     * @return stdClass|null
     */
    public function getError(): ?stdClass {
        return $this->error;
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
     * @param int    $code
     * @param string $message
     *
     * @return Response
     */
    public function setError(int $code, string $message): Response {
        $this->error = new stdClass();
        $this->error->code = $code;
        $this->error->message = $message;
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
     * @param string $key
     * @param mixed  $value
     *
     * @return Response
     */
    public function appendData(string $key, $value): Response {
        if ($this->data)
            $this->data = [];
        $this->data[$key] = $value;
        return $this;
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