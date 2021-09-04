<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core\storage\exceptions;

use \LogicException;
use \Throwable;

/**
 * Description of UnsavableClassException
 *
 * @author acul
 */
class UnsavableClassException extends LogicException {

    public function __construct(string $class, Throwable $previous = null): LogicException {
        $message = 'The class ' . $class . ' is not savable or does not exist.';
        return parent::__construct($message, 0, $previous);
    }

}
