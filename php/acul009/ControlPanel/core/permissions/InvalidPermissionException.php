<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\permissions;

/**
 * Represents an Exception thrown by permission related issues
 *
 * @author acul
 */
class InvalidPermissionException extends \UnexpectedValueException {

    private const STANDART_MESSAGE1 = 'The string "';
    private const STANDART_MESSAGE2 = '" is not a valid permission.';

    public function __construct(string $strPermission, int $code = 0, \Throwable $previous = NULL) {
        $strMessage = self::STANDART_MESSAGE1 . $strPermission . self::STANDART_MESSAGE2;
        parent::__construct($strMessage, $code, $previous);
    }

}
