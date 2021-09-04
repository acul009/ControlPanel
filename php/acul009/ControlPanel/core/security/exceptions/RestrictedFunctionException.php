<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\security\exceptions;

use LogicException;

/**
 * Description of RestrictedFunctionException
 *
 * @author acul
 */
class RestrictedFunctionException extends LogicException {

    public function __construct(string $message = 'You are not allowed to access this function.', int $code = 0, \Throwable $previous = null) {
        return parent::__construct($message, $code, $previous);
    }

}
