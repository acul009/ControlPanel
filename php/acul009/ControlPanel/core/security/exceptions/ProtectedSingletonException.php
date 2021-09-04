<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\security\exceptions;

/**
 * Thrown when trying to create a protected Singleton, that has already been created
 *
 * This should probably instantly exit
 *
 * @author acul
 */
class ProtectedSingletonException extends RestrictedFunctionException {

    public function __construct(string $message = 'This singleton has already been created.', int $code = 0, \Throwable $previous = null) {
        return parent::__construct($message, $code, $previous);
    }

}
