<?php

namespace core\storage;

use \utils\StringTools;
use \Serializable;
use core\storage\exceptions\DirtySavableException;

/**
 * Description of SaveableFilesystemDriver
 *
 * @author acul
 */
abstract class SaveableFilesystemDriver extends SaveableBase {

  private const SAVE_TYPE_ID = 0;
  private const SAVE_TYPE_DATA = 1;
  private const SAVE_KEY_TYPE = 0;
  private const SAVE_KEY_DATA = 1;
  private const STORAGE_SUBFOLDER = 'storage';
  private const DATA_SUBFOLDER = 'data';
  private const ID_FILE = 'id.txt';

  private bool $isSaveTarget = false;
  private bool $isDirty = false;
  private static FilesystemApi $fs;

  public function saveToDatabase(): int {
    $fs = self::$fs;
    $this->isSaveTarget = true;
    $serialized = serialize($this);
    $path = self::getSaveLocation($this->getId());
    $dir = dirname($path);
    if (!$fs->file_exists($dir)) {
      $fs->mkdir($dir, 0777, true);
    }
    $fs->file_put_contents($path, $serialized);
    $this->isSaveTarget = false;
    return $this->getId();
  }

  public static function loadFromIdFromDatabase(int $id): static {
    /*
     * TODO
     */
  }

  private static function getSaveLocation(int $id): string {
    $idString = str_pad((string) $id, 64, '0', STR_PAD_LEFT);
    $splitId = str_split($idString, 3);
    return '/' . self::STORAGE_SUBFOLDER . '/' . $this->getTypename() . '/' . self::DATA_SUBFOLDER . '/' . implode('/', $splitId) . '.txt';
  }

  public static function initDriver(\core\ApiProvider $api): void {
    $this->fs = $api->fs();
  }

  public static function init(): static {
    return static::loadFromId($this->getId());
  }

  private static function getTypename(): string {
    return static::class;
  }

  public function __serialize(): array {
    $arrData = [];
    if (!$this->isSaveTarget) {
      $arrData[self::SAVE_KEY_TYPE] = self::SAVE_TYPE_ID;
      $arrData[self::SAVE_KEY_DATA] = $this->getId();
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
    if ($data[self::SAVE_KEY_TYPE] == self::SAVE_TYPE_DATA) {
      $this->loadDataFromArray($data[self::SAVE_KEY_DATA]);
    } else if ($data[self::SAVE_KEY_TYPE] == self::SAVE_TYPE_ID) {
      $this->id = $data[self::SAVE_KEY_DATA];
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
