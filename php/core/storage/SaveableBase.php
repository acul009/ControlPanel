<?php

namespace core\storage;

use core\security\exceptions\RestrictedFunctionException;
use ReflectionClass;

/**
 * The base class for Savable Drivers - includes basic cache
 *
 * @author acul
 */
abstract class SaveableBase {

  private static SaveableCache $cache;
  private int $id;

  public static abstract function loadFromIdFromDatabase(int $id): static;

  public abstract function saveToDatabase(): int;

  public function save(): int {
    self::$cache->addSaveable($this);
  }

  public function getId(): int {
    return $this->id;
  }

  protected function setId(int $id): void {
    $this->id = $id;
  }

  public static function loadFromId(int $id): static {
    return self::$cache->load(static::class, $id);
  }

  protected static function getCurrentSavableReflection(): ReflectionClass {
    return self::$cache->getSavableReflection(static::class);
  }

  public static final function initSaveableCache(): void {
    if (!isset(self::$cache)) {
      self::$cache = SaveableCache::create();
    }
  }

}
