<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core\storage;

use \acul009\ControlPanel\core\security\exceptions\RestrictedFunctionException;
use \acul009\ControlPanel\core\ApiProvider;
use ReflectionClass;

/**
 * The base class for Savable Drivers - includes basic cache
 *
 * @author acul
 */
abstract class SaveableBase {

    private const SAVE_TYPE_ID = 0;
    private const SAVE_TYPE_DATA = 1;
    private const SAVE_KEY_TYPE = 1;
    private const SAVE_KEY_DATA = 2;
    private const SAVE_KEY_ID = 0;

    private static SaveableCache $cache;
    private int $id = -1;
    private bool $serializeable = false;

    protected function getSerializeable(): bool {
        return $this->serializeable;
    }

    protected function setSerializeable(bool $serializeable): void {
        $this->serializeable = $serializeable;
    }

    public static abstract function loadFromIdFromDatabase(int $id): static;

    public abstract function saveToDatabase(): int;

    public function save(): int {
        $id = $this->saveToDatabase();
        self::$cache->addSaveable($this);
        return $id;
    }

    public abstract function deleteFromDatabase(): void;

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

    protected final function getIndices(): array {
        $rawIndices = $this->generateIndices();
        $preparedIndices = [];
        foreach ($rawIndices as $indexName => $value) {
            if (is_object($value) || is_array($value)) {
                $preparedIndices[$indexName] = serialize($value);
            } else {
                $preparedIndices[$indexName] = $value;
            }
        }
    }

    public function __serialize(): array {
        $arrData = [];
        $arrData[self::SAVE_KEY_ID] = self::getId();
        if (!$this->getSerializeable()) {
            $arrData[self::SAVE_KEY_TYPE] = self::SAVE_TYPE_ID;
        } else {
            if ($this->isDirty) {
                throw new DirtySavableException();
            }
            $closure = \Closure::bind(function () {
                        return get_object_vars($this);
                    }, $this, static::class);
            $arrData[self::SAVE_KEY_TYPE] = self::SAVE_TYPE_DATA;
            $arrData[self::SAVE_KEY_DATA] = $closure();
        }
        return $arrData;
    }

    public function __unserialize(array $data): void {
        self::setId($data[self::SAVE_KEY_ID]);
        if ($data[self::SAVE_KEY_TYPE] == self::SAVE_TYPE_DATA) {
            $this->loadDataFromArray($data[self::SAVE_KEY_DATA]);
        } else if ($data[self::SAVE_KEY_TYPE] == self::SAVE_TYPE_ID) {
            $this->isDirty = true;
        } else {
            /*
             * TODO
             */
            throw new \Exception();
        }
    }

    protected function loadDataFromArray(array $data): void {
        /*
         * Pass by reference is extremly important here!
         * Without it references will get lost during unserialization
         */
        $closure = \Closure::bind(function (array $data) {
                    foreach ($data as $key => &$value) {
                        $this->$key = &$value;
                    }
                }, $this, static::class);
        $closure($data);
    }

    /**
     * You can use this function to load your saveableObjects by filtering
     * with your indices.
     * <br>
     * This function is meant to be used in a wrapper function e.g.:
     * <br>
     * <pre>
     * public function loadByUsername(string $username){
     * &nbsp;&nbsp; return self::loadFromFilter(SaveableFilter::equals('username', $username);
     * }
     * </pre>
     *
     * @return array The found objects
     */
    protected abstract function loadFromFilter(SaveableFilter $filter): array;

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
    protected abstract function generateIndices(): IndexCollection|null;
}
