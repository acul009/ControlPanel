<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core\Storage\Exceptions;

use UnexpectedValueException;

/**
 * <pre>
 * Thrown when trying to load an ID that doesn't exist (yet).
 * </pre>
 *
 * @author acul
 */
class UnknownIdException extends UnexpectedValueException {

    public function __construct(int $id, int $code = 0, \Throwable $previous = NULL) {
        $message = 'The given Id "' . $id . '" could not be found.';
        parent::__construct($message, $code, $previous);
    }

}
