<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core\storage;

interface SaveableDriverInterface {

    public function saveToDatabase(): int;

    public static function loadFromIdFromDatabase(int $id): static;
}
