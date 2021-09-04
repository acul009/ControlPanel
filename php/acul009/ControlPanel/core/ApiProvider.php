<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core;

use \acul009\ControlPanel\core\storage\FilesystemApi;
use \acul009\ControlPanel\core\LibraryManager2;
use \acul009\ControlPanel\core\security\ProtectedSingleton;

/**
 * The ApiProvider supplies others with the neccesary api access.
 *
 * @author acul
 */
class ApiProvider extends ProtectedSingleton {

    private FilesystemApi $filesystem;

    protected function init(LibraryManager2 $lib = null): void {
        $this->filesystem = FilesystemApi::create($lib);
    }

    public function fs(): FilesystemApi {
        return $this->filesystem;
    }

}
