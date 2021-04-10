<?php

namespace core\storage;

use core\drivers\SaveableDriver;
use core\security\exceptions\RestrictedFunctionException;
use ReflectionClass;

/**
 * This class can be used as a Parent if you need to store the object between runs.
 * Depending on the current driver, objects may need to declare additional functions.
 * <br><br>
 * additionally it is nessecary to call init() on an Object which was not loaded explicitly.
 *
 * @author acul
 */
abstract class SaveableObject extends SaveableDriver {

  protected static final function getCurrentSavableReflection(): ReflectionClass {
    throw new RestrictedFunctionException();
  }

}
