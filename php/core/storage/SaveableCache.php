<?php

namespace core\storage;

use core\security\ProtectedSingleton;
use \WeakRef;
use \ReflectionClass;

/**
 * This is the class responsible for managing the cached SavableObjects.
 *
 * @author acul
 */
class SaveableCache extends ProtectedSingleton {

  private array $instanceCache = [];
  private array $reflectionCache = [];

  public function load(string $class, int $id): SaveableObject {
    $reflection = $this->getSavableReflection($class);
    if (!isset($instanceCache[$class])) {
      $instanceCache[$class] = [];
    }
    if (!isset($instanceCache[$class][$id]) || (($saveable = $instanceCache[$class][$id]->acquire()) === null)) {
      $saveable = $class::loadFromIdFromDatabase($id);
      $instanceCache[$class][$id] = new WeakRef($saveable);
    }
    return $saveable;
  }

  public function getSavableReflection(string $class): ReflectionClass {
    if (!isset($reflectionCache[$class])) {
      try {
        $reflection = new ReflectionClass($class);
        if ($reflection->isSubclassOf(SaveableObject::class) && $reflection->isInstantiable()) {
          $reflectionCache[$class] = $reflection;
        } else {
          $reflectionCache[$class] = false;
        }
      } catch (ReflectionException $ex) {
        throw new UnsavableClassException($class, $ex);
      }
    }
    if ($reflectionCache[$class] === false) {
      throw new UnsavableClassException($class);
    }
    return $reflectionCache[$class];
  }

  protected function init(): void {

  }

}