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
    
    public abstract function deleteFromDatabase(): int;
    
    public function delete(): void {
        self::$cache->removeSaveable($this);
        $this->deleteFromDatabase();
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

    public abstract static function initDriver(ApiProvider $api): void;

    public static final function initSaveableCache(): void {
        if (!isset(self::$cache)) {
            self::$cache = SaveableCache::create();
        }
    }
    
    protected final function getIndices() : array {
        $rawIndices = $this->generateIndices();
        $preparedIndices = [];
        foreach($rawIndices as $indexName => $value) {
            if(is_object($value) || is_array($value)) {
                $preparedIndices[$indexName] = serialize($value);
            } else {
                $preparedIndices[$indexName] = $value;
            }
        }
    }
    
    /**
     * Use this function to return an associative array of values you can later
     * filter for.
     * <br>
     * These values are <b>ONLY</b> updated when saving, so try to avoid dynamic values
     * which are not under your control.
     * <br>
     * <br>
     * Numbers can be filtered with &lt; and &gt;.
     * Other values can only be filtered for an exact match.
     * <br>
     * <br>
     * The PHP serialize function is used to turn objects and arrays into strings.
     * These strings are then hashed and used for comparison.
     * <br>
     * <br>
     * <b>Filters are effected by things like array order! Sort arrays before returning to avoid confusion.</b>
     */
    protected abstract function generateIndices(): array;

}
