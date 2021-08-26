<?php

declare(strict_types=1);

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
        $id = $saveable->getId();
        $instanceCache[$class][$id] = WeakReference::create($saveable);
    }
    
    public function removeSaveable(SaveableObject $saveable) {
        $class = get_class($saveable);
        $id = $saveable->getId();
        unset($instanceCache[$class][$id]);
    }

    private function createMissingArrays(string $class): void {
        $reflection = $this->getSavableReflection($class);
        if (!isset($instanceCache[$class])) {
            $instanceCache[$class] = [];
        }
    }

    protected function init(): void {
        
    }

}
