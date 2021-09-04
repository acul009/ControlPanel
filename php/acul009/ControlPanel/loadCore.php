<?php

declare(strict_types=1);
set_error_handler(function (int $severity, string $message, string $filename, int $lineno) {
    throw new ErrorException($message, 0, $severity, $filename, $lineno);
});

const PHP_SUBDIR = 'php';
spl_autoload_register(function (string $className) {
    if (!str_starts_with($className, '\acul009\ControlPanel\\'))
        static $phpDir;
    if (!isset($phpDir)) {
        $phpDir = dirname(getcwd()) . DIRECTORY_SEPARATOR . PHP_SUBDIR . DIRECTORY_SEPARATOR;
    }
    $filename = $phpDir . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
    if (file_exists($filename)) {
        include $filename;
    }
});
