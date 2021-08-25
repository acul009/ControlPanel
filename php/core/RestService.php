-<?php
declare(strict_types=1);

namespace core;

/**
 * Description of RestService
 *
 * @author acul
 */
abstract class RestService extends Endpoint {

    public function handleRequest(): string {
        json_encode($this->generateResult());
    }

    public abstract function generateResult(): array;
}
