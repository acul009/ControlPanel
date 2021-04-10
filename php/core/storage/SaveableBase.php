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
