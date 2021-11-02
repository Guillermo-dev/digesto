<?php

/**
 * @param int    $code
 * @param string $message
 *
 * @throws Exception
 */
function errorHandler(int $code, string $message): void {
    throw new Exception($message, $code);
}

set_error_handler('errorHandler', E_ALL);