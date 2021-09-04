<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\storage;

/**
 * Description of StringSaveDriver
 *
 * @author acul
 */
interface StringSaveDriver {

    public function save(string $type, int $id, string $content): void;

    public function load(string $type, int $id): string;
}