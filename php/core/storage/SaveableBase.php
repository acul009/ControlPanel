<?php

declare(strict_types=1);

namespace core\storage;

use core\security\exceptions\RestrictedFunctionException;
use core\ApiProvider;
use ReflectionClass;

/**
 * The base class for Savable Drivers - includes basic cache
 *
 * @author acul
 */
abstract class SaveableBase {

    private static SaveableCache $cache;
    private int $id = -1;

    public static abstract function loadFromIdFromDatabase(int $id): static;

    public abstract function saveToDatabase(): int;

    public function save(): int {
        $id = $this->saveToDatabase();
        self::$cache->addSaveable($this);
        return $id;
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

    public abstract static function initDriver(ApiProvider $api): void;

    public static final function initSaveableCache(): void {
        if (!isset(self::$cache)) {
            self::$cache = SaveableCache::create();
        }
    }

}
