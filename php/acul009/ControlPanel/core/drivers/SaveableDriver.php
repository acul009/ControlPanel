<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core\drivers;

use \acul009\ControlPanel\core\storage\SaveableFilesystemDriver;
use \acul009\ControlPanel\core\storage\SaveableDriverInterface;

/**
 * Replace the parent class to change the driver used for SavableObject
 * The Driver should implement the \core\storage\SaveableDriverInterface
 *
 * @author acul
 */
abstract class SaveableDriver extends SaveableFilesystemDriver implements SaveableDriverInterface {
    
}
