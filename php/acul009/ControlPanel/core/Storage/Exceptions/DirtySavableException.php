<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core\Storage\Exceptions;

use \LogicException;

/**
 * Caused by trying to use a dirty
 *
 * @author acul
 */
class DirtySavableException extends LogicException {

    public function __construct(string $message = 'The given SavableObject was not yet replaced with init()', int $code = 0, \Throwable $previous = null): LogicException {
        return parent::__construct($message, $code, $previous);
    }

}
