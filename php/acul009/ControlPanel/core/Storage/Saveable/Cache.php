<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core\Storage\Saveable;

use \acul009\ControlPanel\core\security\ProtectedSingleton;
use \WeakReference;

/**
 * This is the class responsible for managing the cached SavableObjects.
 *
 * @author acul
 */
class Cache extends ProtectedSingleton {

    private array $instanceCache = [];

    public function load(string $class, int $id): SaveableObject {
        $this->createMissingArrays($class);
        if (!isset($this->instanceCache[$class])) {
            $this->instanceCache[$class] = [];
        }
        if (!isset($this->instanceCache[$class][$id]) || (($saveable = $this->instanceCache[$class][$id]->get()) === null)) {
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
        if (!isset($this->instanceCache[$class])) {
            $this->instanceCache[$class] = [];
        }
    }

    protected function init(): void {

    }

}
