<?php

namespace core\drivers;

use core\storage\SaveableFilesystemDriver;

/**
 * Replace the parent class to change the driver used for SavableObject
 *
 * @author acul
 */
abstract class SaveableDriver extends SaveableFilesystemDriver {

}
