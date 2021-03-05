<?php

namespace core\storage;

use core\drivers\SaveableDriver;

/**
 * This class can be used as a Parent if you need to store the object between runs.
 * Depending on the current driver, objects may need to declare additional functions.
 *
 * @author acul
 */
abstract class SaveableObject extends SaveableDriver {

}
