<?php

namespace core\storage;

interface Saveable {

  /**
   * @return int id under which the Entry was saved
   */
  function save(int $id = null): int;

  static function loadFromId(int $id): self;
}
