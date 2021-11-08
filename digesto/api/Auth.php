<?php

namespace api;

use Exception;
use models\Usuario;
use Google_Client;

use api\util\Request;
use api\util\Response;
use api\exceptions\ApiException;

/**
 * Class Auth
 *
 * @package api
 */
abstract class Auth {
    /**
     *
     * @throws Exception
     */
    public static function login(): void {
        if (isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $data = Request::getBodyAsJson();
        $google_client = new Google_Client(['client_id' => '471191857447-594hnuasrs49qe51amgma6o0hiir07de.apps.googleusercontent.com']);

        $payload = $google_client->verifyIdToken($data->gToken);
        if (!$payload)
            throw new ApiException('Error inesperado, intentelo mas tarde');

        $usuario = Usuario::getUsuarioByEmail($payload["email"]);
        if (!$usuario)
            throw new ApiException('Usuario no autorizado', Response::NOT_FOUND);

        $_SESSION['user'] = serialize($usuario);
    }

    /**
     *
     * @throws Exception
     */
    public static function logout(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);
        session_destroy();
    }
}
