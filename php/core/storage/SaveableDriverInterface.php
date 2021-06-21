<?php

namespace core\storage;

interface SaveableDriverInterface {

    public function saveToDatabase(): int;
    
    public static function loadFromIdFromDatabase(int $id): static;
}
