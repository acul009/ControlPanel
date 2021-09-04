<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core\Storage\Saveable;

/**
 * Just a trick to be able to call parent in the saveable Base
 *
 * @author acul
 */
abstract class IdObject {

    private int $id = -1;

    public function getId(): int {
        return $this->id;
    }

    protected function setId(int $id): void {
        $this->id = $id;
    }

}
