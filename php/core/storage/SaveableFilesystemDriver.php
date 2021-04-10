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
abstract class SaveableFilesystemDriver extends SaveableBase implements Serializable {

  private const ID_PREFIX = 'ID:';
  private const DATA_PREFIX = 'DATA:';

  private int $id;
  private bool $isSaveTarget = false;
  private bool $isDirty = false;

  public function getId(): int {
    return $this->id;
  }

  private function setId(int $id): void {
    $this->id = $id;
  }

  public function save(int $id = null): int {
    $this->isSaveTarget = true;
    $serialized = serialize($this);
    /*
     * TODO
     */
    $this->isSaveTarget = false;
    return $this->getId();
  }

  public static function loadFromIdFromDatabase(int $id): static {
    /*
     * TODO
     */
  }

  public static function init(): static {
    return static::loadFromId($this->getId());
  }

  protected function getTypename(): string {
    return static::class;
  }

  public function serialize(): string {
    if (!$this->isSaveTarget) {
      return self::ID_PREFIX . $this->getId();
    }
    if ($this->isDirty) {
      throw new DirtySavableException();
    }
    $closure = \Closure::bind(function () {
              return get_object_vars($this);
            }, $this, static::class);
    return self::DATA_PREFIX . serialize($closure());
    /*
     * TODO
     */
  }

  public function unserialize(string $serialized): void {
    print_r('<br><br>Raw Data:<br>' . $serialized);
    if (StringTools::startsWith($serialized, self::ID_PREFIX)) {
      $this->id = intval(substr($serialized, strlen(self::ID_PREFIX)));
      $this->isDirty = true;
      return;
    }
    if (StringTools::startsWith($serialized, self::DATA_PREFIX)) {
      $this->loadDataFromSerializedString(substr($serialized, strlen(self::DATA_PREFIX)));
      return;
    }
    /*
     * TODO
     */
    throw new \Exception();
  }

  protected function loadDataFromSerializedString(string $serialized): void {
    $objectVars = unserialize($serialized);
    var_dump($objectVars);
    $closure = \Closure::bind(function (array $objectVars) {
              foreach ($objectVars as $key => &$value) {
                $this->$key = &$value;
              }
            }, $this, static::class);
    $closure($objectVars);
  }

}
