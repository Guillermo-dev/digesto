<?php

namespace api;

use Exception;
use helpers\Response;
use helpers\Request;
use models\Usuario;
use Google_Client;



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
            throw new Exception('Forbidden', 403);
        
        $data = Request::getBodyAsJson();
        $google_client = new Google_Client(['client_id' => '471191857447-594hnuasrs49qe51amgma6o0hiir07de.apps.googleusercontent.com']);

        $payload = $google_client->verifyIdToken($data->gToken);
        if (!$payload)
            throw new Exception('Error inesperado, intentelo mas tarde');

        $usuario = Usuario::getUsuarioByEmail($payload["email"]);
        if (!$usuario)
            throw new Exception('Usuario no autorizado', 404);

        $_SESSION['user'] = serialize($usuario);

        Response::getResponse()->setStatus('success');
    }

    /**
     *
     * @throws Exception
     */
    public static function logout(): void
    {
        if (!isset($_SESSION['user']))
            throw new Exception('Forbidden', 403);

        session_destroy();
        Response::getResponse()->setStatus('success');
    }
}
