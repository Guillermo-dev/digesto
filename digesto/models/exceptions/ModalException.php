<?php

namespace models\exceptions;

use Exception;
use Throwable;

/**
 * Class ModalException
 *
 * @package models\exceptions
 */
class ModalException extends Exception {
    /**
     * ModalException constructor.
     *
     * @param string         $message
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}