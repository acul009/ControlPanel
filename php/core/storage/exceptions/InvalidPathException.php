<?php

declare(strict_types=1);

namespace core\storage\exceptions;

use UnexpectedValueException;

/**
 * <pre>
 * Thrown when a path given to the Filesystem Api tries to break out of the data directory
 * or is invalid.
 * </pre>
 * @author acul
 */
class InvalidPathException extends UnexpectedValueException {

    public function __construct(string $path = "", int $code = 0, \Throwable $previous = NULL) {
        $message = 'The given Path "' . $path . '" is invalid or forbidden.';
        parent::__construct($message, $code, $previous);
    }

}
