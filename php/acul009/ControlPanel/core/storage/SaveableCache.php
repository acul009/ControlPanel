<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\storage;

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

    public function load(string $class, int $id): SaveableObject {
        $this->createMissingArrays($class);
        if (!isset($this->instanceCache[$class])) {
            $this->instanceCache[$class] = [];
        }
        if (!isset($this->instanceCache[$class][$id]) || (($saveable = $this->instanceCache[$class][$id]->acquire()) === null)) {
            $saveable = $class::loadFromIdFromDatabase($id);
            $this->instanceCache[$class][$id] = WeakReference::create($saveable);
        }
        return $saveable;
    }

    public function addSaveable(SaveableObject $saveable) {
        $class = get_class($saveable);
        $this->createMissingArrays($class);
        $id = $saveable->getId();
        $this->instanceCache[$class][$id] = WeakReference::create($saveable);
    }
    
    public function removeSaveable(SaveableObject $saveable) {
        $class = get_class($saveable);
        $id = $saveable->getId();
        unset($this->instanceCache[$class][$id]);
    }

    private function createMissingArrays(string $class): void {
        $reflection = $this->getSavableReflection($class);
        if (!isset($this->instanceCache[$class])) {
            $this->instanceCache[$class] = [];
        }
    }

    protected function init(): void {
        
    }

}
