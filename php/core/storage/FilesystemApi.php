<?php

namespace core\storage;

use \core\security\ProtectedSingleton;
use \core\LibraryManager2;

/**
 * <pre>
 * This api redirects all filesystem calls to the respective data directory.
 * Right now only absolute paths are allowed.
 * Support for relative paths would be pretty easy to implement,
 * but it might take up quite a bit of performance.
 * For now I'll leave it like this.
 * </pre>
 *
 * @author acul
 */
class FilesystemApi extends ProtectedSingleton {

  private LibraryManager2 $lib;

  protected function init(LibraryManager2 $lib): void {
    $this->lib = $lib;
  }

  public function isPathAllowed(string $path): bool {
    return strpos($path, '..') === false;
  }

  public function throwExceptionIfPathNotAllowed(string $path) {
    if (!$this->isPathAllowed($path)) {
      throw new InvalidPathException($path);
    }
  }

  private function getFullPath(string $path): string {
    $this->throwExceptionIfPathNotAllowed($path);
    /*
     * TODO: load the currently active module from somewhere
     */
    $module = '';
    $dataDir = $this->lib->getDataDir();
    throw new Exception('Not implemented yet');
    return $dataDir . '/' . $module . ($path[0] === '/' ? '' : '/') . $path;
  }

  public function file_get_contents(string $filename, bool $use_include_path = false, resource $context = null, int $offset = 0, int $maxlen = null): string|false {
    return file_get_contents($this->getFullPath($filename), $use_include_path, $context, $offset, $maxlen);
  }

}
