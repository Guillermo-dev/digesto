<?php

namespace api;

use Exception;
use helpers\Response;
use helpers\Request;
use models\Tag;
use models\Permiso;

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
        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function getTag(int $id = 0): void {
        Response::getResponse()->appendData('tag', Tag::getTagById($id));
        Response::getResponse()->setStatus('success');
    }

    /**
     * @throws Exception
     */
    public static function createTag(): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Unauthorized', 401);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('tags_create', $usuarioId))
            throw new Exception('Forbidden', 403);

        $tagData = Request::getBodyAsJson();

        if (!isset($tagData->nombre))
            throw new Exception('Bad Request', 400);

        $tag = new Tag();
        $tag->setNombre($tag->nombre);

        Tag::createTag($tag);

        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function updateTag(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Unauthorized', 401);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('ags_update', $usuarioId))
            throw new Exception('Forbidden', 403);

        $tagData = Request::getBodyAsJson();

        if (!isset($tagData->tagId))
            throw new Exception('Bad Request', 400);
        if (!isset($tagData->nombre))
            throw new Exception('Bad Request', 400);

        $tag = Tag::getTagById($id);
        if (!$tag) throw new Exception('El tag no existe', 404);

        $tag->setNombre($tag->nombre);

        Tag::updateTag($tag);

        Response::getResponse()->setStatus('success');
    }

    /**
     * @param int $id
     *
     * @throws Exception
     */
    public static function deleteTag(int $id = 0): void {
        if (!isset($_SESSION['user']))
            throw new Exception('Unauthorized', 401);

        $usuarioId = unserialize($_SESSION['user'])->getId();
        if (!Permiso::hasPermiso('tags_delete', $usuarioId))
            throw new Exception('Forbidden', 403);

        $tag = Tag::getTagById($id);
        if (!$tag) throw new Exception('El tag no existe', 404);

        Tag::deleteTag($tag->getId());

        Response::getResponse()->setStatus('success');
    }
}
