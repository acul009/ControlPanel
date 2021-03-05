<?php

namespace core\storage;

/**
 * Description of SaveableFilesystemDriver
 *
 * @author acul
 */
abstract class SaveableFilesystemDriver implements Saveable {

  public function save(int $id = null): int {

  }

  public static function loadFromId(int $id): Saveable {

  }

  protected function getTypename(): string {
    return static::class;
  }

}
