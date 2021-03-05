<?php

namespace core;

use core\storage\FilesystemApi;
use core\LibraryManager2;

/**
 * The ApiProvider supplies others with the neccesary api access.
 *
 * @author acul
 */
class ApiProvider extends ProtectedSingleton {

  private FilesystemApi $filesystem;

  protected function init(LibraryManager2 $lib): void {
    $this->filesystem = FilesystemApi::create();
  }

  public function fs(): FilesystemApi {
    return $this->filesystem;
  }

}
