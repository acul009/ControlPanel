<?php

declare(strict_types=1);

namespace core\storage;

use \utils\StringTools;
use \Serializable;
use core\storage\exceptions\DirtySavableException;
use core\storage\exceptions\UnknownIdException;

/**
 * This is a simple driver, which stores the object data inside the data directory.
 * <br>
 * It loads and saves objects by creating a unique path from their id.
 * <br>
 * This allows the driver to quickly load and save objects by using
 * the filesystem index.
 *
 * @author acul
 */
abstract class SaveableFilesystemDriver extends SaveableBase {

    private const SAVE_TYPE_ID = 0;
    private const SAVE_TYPE_DATA = 1;
    private const SAVE_KEY_TYPE = 1;
    private const SAVE_KEY_DATA = 2;
    private const SAVE_KEY_ID = 0;
    private const STORAGE_SUBFOLDER = 'SaveableObjects';
    private const DATA_SUBFOLDER = 'data';
    private const ID_FILE = 'id.bin';

    private bool $isSaveTarget = false;
    private bool $isDirty = false;
    private static FilesystemApi $fs;

    public function saveToDatabase(): int {
        $fs = self::$fs;
        $this->isSaveTarget = true;
        $dir = self::getSavePrefix();
        if (!$fs->file_exists($dir)) {
            $fs->mkdir($dir, 0777, true);
        }
        if (parent::getId() < 0) {
            parent::setId(self::generateId());
        }
        $path = self::getSaveLocation(parent::getId());
        $dir = dirname($path);
        if (!$fs->file_exists($dir)) {
            $fs->mkdir($dir, 0777, true);
        }
        $serialized = serialize($this);
        $file = $fs->fopen($path, 'w');
        flock($file, LOCK_EX);
        fwrite($file, $serialized);
        fflush($file);
        flock($file, LOCK_UN);
        fclose($file);
        $this->isSaveTarget = false;
        return parent::getId();
    }

    private static function generateId() {
        $fs = self::$fs;
        $filename = self::getSavePrefix() . self::ID_FILE;
        $id = 0;
        $file = $fs->fopen($filename, 'c+b');
        flock($file, LOCK_EX);
        $byteArray = fread($file, 8);
        if ($byteArray) {
            for ($i = 0; $i < strlen($byteArray); $i++) {
                $byte = $byteArray[$i];
                $id = ($id << 8) + ord($byte);
            }
            $id++;
        }
        $byteArray = '';
        for ($i = 0; $i < 8; $i++) {
            $byte = ($id >> (8 * $i)) & 0b11111111;
            $byteArray = chr($byte) . $byteArray;
        }
        for ($i = 0; $i < strlen($byteArray); $i++) {
            $byte = $byteArray[$i];
        }
        ftruncate($file, 0);
        rewind($file);
        fwrite($file, $byteArray);
        fflush($file);
        flock($file, LOCK_UN);
        fclose($file);
        return $id;
    }

    public static function loadFromIdFromDatabase(int $id): static {
        $fs = self::$fs;
        $path = self::getSaveLocation($id);
        if (!$fs->file_exists($path)) {
            throw new UnknownIdException($id);
        }
        $file = $fs->fopen($path, 'r');
        flock($file, LOCK_EX);
        $serialzed = stream_get_contents($file);
        flock($file, LOCK_UN);
        fclose($file);
        return unserialize($serialzed);
    }

    private static function getSaveLocation(int $id): string {
        $idString = str_pad((string) $id, 21, '0', STR_PAD_LEFT);
        $splitId = str_split($idString, 3);
        return self::getSavePrefix() . self::DATA_SUBFOLDER . '/' . implode('/', $splitId) . '.txt';
    }

    private static function getSavePrefix(): string {
        static $cache = [];
        $type = self::getTypename();
        if (!isset($cache[$type])) {
            $cache[$type] = '/' . self::STORAGE_SUBFOLDER . '/' . self::getTypename() . '/';
        }
        return $cache[$type];
    }

    public static function initDriver(\core\ApiProvider $api): void {
        self::$fs = $api->fs();
    }

    public static function init(): static {
        if ($this->isDirty) {
            return static::loadFromId(parent::getId());
        }
        return $this;
    }

    private static function getTypename(): string {
        return str_replace('\\', '/', static::class);
    }

    public function __serialize(): array {
        $arrData = [];
        $arrData[self::SAVE_KEY_ID] = parent::getId();
        if (!$this->isSaveTarget) {
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
        parent::setId($data[self::SAVE_KEY_ID]);
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

}
