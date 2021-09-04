<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\storage;

use core\drivers\SaveableDriver;
use core\security\exceptions\RestrictedFunctionException;

/**
 * This class can be used as a Parent if you need to store the object between runs.
 * Depending on the current driver, objects may need to declare additional functions.
 * <br><br>
 * additionally it is nessecary to call init() on an Object which was not loaded
 * explicitly to get a proper instance you can then work with.
 *
 * @author acul
 */
abstract class SaveableObject extends SaveableDriver {

    protected function setId(int $id): void {
        throw new RestrictedFunctionException();
    }

}
