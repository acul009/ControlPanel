<?php

namespace core\storage;

/**
 * Description of StringSaveDriver
 *
 * @author acul
 */
interface StringSaveDriver {

  public function save(string $type, int $id, string $content): void;

  public function load(string $type, int $id): string;
}
