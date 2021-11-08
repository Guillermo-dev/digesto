<?php

namespace api;

use Exception;
use models\Tag;
use models\Permiso;
use api\util\Request;
use api\util\Response;
use api\exceptions\ApiException;

/**
 * Class Tags
 *
 * @package api
 */
abstract class Tags {

    /**
     * @throws Exception
     */
    public static function getTags(): void {
        Response::getResponse()->appendData('tags', Tag::getTags());
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getTag(int $id = 0): void {
        Response::getResponse()->appendData('tag', Tag::getTagById($id));
    }

    /**
     * @throws Exception
     */
    public static function createTag(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('tags_create', $usuarioId))
            throw new ApiException('Forbidden', Response::RESPONSE_FORBIDDEN);

        $tagData = Request::getBodyAsJson();

        $tag = new Tag();

        if (isset($tagData->nombre))
            $tag->setNombre($tagData->nombre);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        Tag::createTag($tag);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateTag(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('ags_update', $usuarioId))
            throw new ApiException('Forbidden', Response::RESPONSE_FORBIDDEN);

        $tagData = Request::getBodyAsJson();

        $tag = Tag::getTagById($id);
        if (!$tag)
            throw new ApiException('El tag no existe', Response::RESPONSE_NOT_FOUND);

        if (isset($tagData->nombre))
            $tag->setNombre($tagData->nombre);
        else throw new ApiException('Bad Request', Response::RESPONSE_BAD_REQUEST);

        Tag::updateTag($tag);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteTag(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::RESPONSE_UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('tags_delete', $usuarioId))
            throw new ApiException('Forbidden', Response::RESPONSE_FORBIDDEN);

        $tag = Tag::getTagById($id);
        if (!$tag)
            throw new ApiException('El tag no existe', Response::RESPONSE_NOT_FOUND);

        Tag::deleteTag($tag->getId());
    }
}
