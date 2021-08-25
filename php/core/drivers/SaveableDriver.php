<?php

declare(strict_types=1);

namespace core\drivers;

use core\storage\SaveableFilesystemDriver;
use core\storage\SaveableDriverInterface;

/**
 * Replace the parent class to change the driver used for SavableObject
 * The Driver should implement the \core\storage\SaveableDriverInterface
 *
 * @author acul
 */
abstract class SaveableDriver extends SaveableFilesystemDriver implements SaveableDriverInterface {
    
}
