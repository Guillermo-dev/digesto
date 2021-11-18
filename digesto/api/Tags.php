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
        if (isset($_GET['tags']))
        Response::getResponse()->appendData('tags', Tag::getTagsByText($_GET['tags']));

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
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('tags_create', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $tagData = Request::getBodyAsJson();

        $tag = new Tag();

        if (isset($tagData->nombre))
            $tag->setNombre($tagData->nombre);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        Tag::createTag($tag);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateTag(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('ags_update', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $tagData = Request::getBodyAsJson();

        $tag = Tag::getTagById($id);
        if (!$tag)
            throw new ApiException('El tag no existe', Response::NOT_FOUND);

        if (isset($tagData->nombre))
            $tag->setNombre($tagData->nombre);
        else throw new ApiException('Bad Request', Response::BAD_REQUEST);

        Tag::updateTag($tag);
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteTag(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('tags_delete', $usuarioId))
            throw new ApiException('Forbidden', Response::FORBIDDEN);

        $tag = Tag::getTagById($id);
        if (!$tag)
            throw new ApiException('El tag no existe', Response::NOT_FOUND);

        Tag::deleteTag($tag->getId());
    }

    public static function tagsSearch(): void {
        if (!isset($_SESSION['user']))
            throw new ApiException('Unauthorized', Response::UNAUTHORIZED);

        if (!isset($_GET['tags']))
            throw new ApiException('bat request', Response::BAD_REQUEST);

        $tagText = $_GET['tags'];
        Response::getResponse()->appendData('tags', Tag::getTagsByText($tagText));
    }
}
