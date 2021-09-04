<?php

declare(strict_types=1);

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\storage;

use \core\security\ProtectedSingleton;
use \core\LibraryManager2;
use core\storage\exceptions\InvalidPathException;

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

    protected function init(LibraryManager2 $lib = null): void {
        $this->lib = $lib;
    }

    public function isPathAllowed(string $path): bool {
        return isset($this->getFullPath($path, false)[0]);
    }

    private function getFullPath(string $path, bool $throwExeption = true): string {
        $dataDir = $this->lib->getDataDir();
        $relativePath = $dataDir . ($path[0] === '/' ? '' : '/') . $path;
        $realPath = $this->realpath($relativePath);
        if (str_starts_with($realPath, $dataDir)) {
            return $realPath;
        }
        if ($throwExeption) {
            throw new InvalidPathException($path);
        }
        return '';
    }

    public function realpath(string $path): string {
        /*
         * TODO
         */
        return $path;
    }

    public function file_get_contents(string $filename, bool $use_include_path = false, $context = null, int $offset = 0, int $maxlen = null): string|false {
        return file_get_contents($this->getFullPath($filename), $use_include_path, $context, $offset, $maxlen);
    }

    public function file_put_contents(string $filename, mixed $data, int $flags = 0, $context = null): int {
        return file_put_contents($this->getFullPath($filename), $data, $flags, $context);
    }

    public function mkdir(string $pathname, int $mode = 0777, bool $recursive = false, $context = null): bool {
        return mkdir($this->getFullPath($pathname), $mode, $recursive, $context);
    }

    public function file_exists(string $filename): bool {
        return file_exists($this->getFullPath($filename));
    }

    public function fopen(string $filename, string $mode, bool $use_include_path = false, $context = null) {
        return fopen($this->getFullPath($filename), $mode, $use_include_path, $context);
    }

}
