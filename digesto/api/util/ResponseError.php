<?php

namespace api\util;

use JsonSerializable;

/**
 * Class ResponseError
 *
 * @package api\util
 */
class ResponseError implements JsonSerializable {
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $message;

    /**
     * ResponseError constructor.
     *
     * @param int    $code
     * @param string $message
     */
    public function __construct(int $code, string $message) {
        $this->code = $code;
        $this->message = $message;
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
    public function getMessage(): string {
        return $this->message;
    }

    /**
     * @param int $code
     *
     * @return ResponseError
     */
    public function setCode(int $code): ResponseError {
        $this->code = $code;
        return $this;
    }

    /**
     * @param string $message
     *
     * @return ResponseError
     */
    public function setMessage(string $message): ResponseError {
        $this->message = $message;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array {
        return get_object_vars($this);
    }
}