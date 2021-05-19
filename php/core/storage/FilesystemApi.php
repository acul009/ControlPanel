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
    return isset($this->getFullPath($path, false)[0]);
  }

  private function getFullPath(string $path, bool $throwExeption): string {
    /*
     * TODO: load the currently active module from somewhere
     */
    $module = '';
    $dataDir = $this->lib->getDataDir();
    $pathPrefix = $dataDir . '/' . $module;
    $relativePath = $pathPrefix . ($path[0] === '/' ? '' : '/') . $path;
    $realPath = realpath($relativePath);
    if (str_starts_with($realPath, $pathPrefix)) {
      return $realPath;
    }
    if ($throwExeption) {
      throw new InvalidPathException($path);
    }
    return '';
  }

  public function file_get_contents(string $filename, bool $use_include_path = false, $context = null, int $offset = 0, int $maxlen = null): string|false {
    return file_get_contents($this->getFullPath($filename), $use_include_path, $context, $offset, $maxlen);
  }

  public function file_put_contents(string $filename, mixed $data, int $flags = 0, $context = null) {
    file_put_contents($this->getFullPath($filename), $data, $flags, $context);
  }

  public function mkdir(string $pathname, int $mode, bool $recursive, $context = null) {
    mkdir($this->getFullPath($pathname), $mode, $recursive, $context);
  }

}
