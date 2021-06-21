<?php

namespace core\storage;

use core\security\ProtectedSingleton;
use \WeakReference;
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
    $this->createMissingArrays($class);
    if (!isset($instanceCache[$class])) {
      $instanceCache[$class] = [];
    }
    if (!isset($instanceCache[$class][$id]) || (($saveable = $instanceCache[$class][$id]->acquire()) === null)) {
      $saveable = $class::loadFromIdFromDatabase($id);
      $instanceCache[$class][$id] = WeakReference::create($saveable);
    }
    return $saveable;
  }

  public function addSaveable(SaveableObject $saveable) {
    $class = get_class($saveable);
    $this->createMissingArrays($class);
    $reflection = $this->getSavableReflection($class);
    $id = $saveable->getId();
    $instanceCache[$class][$id] = WeakReference::create($saveable);
  }

  private function createMissingArrays(string $class): void {
    $reflection = $this->getSavableReflection($class);
    if (!isset($instanceCache[$class])) {
      $instanceCache[$class] = [];
    }
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
