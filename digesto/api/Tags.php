<?php

namespace api;

use Exception;
use helpers\Response;
use models\Tag;

abstract class Tags {

  public static function getTags(): void {
    Response::getResponse()->appendData('tags', Tag::getTags());
    Response::getResponse()->setStatus('success');
  }

  public static function getTag(int $id = 0): void {
    Response::getResponse()->appendData('tag', Tag::getTagById($id));
    Response::getResponse()->setStatus('success');
  }

  public static function createTag(): void {
    throw new Exception('Not implemented', 504);
  }

  public static function updateTag(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }

  public static function deleteTag(int $id = 0): void {
    throw new Exception('Not implemented', 504);
  }
}
