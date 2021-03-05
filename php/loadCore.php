<?php

const PHP_SUBDIR = 'php';
spl_autoload_register(function(string $className) {
  static $phpDir;
  if (!isset($phpDir)) {
    $phpDir = dirname(getcwd()) . DIRECTORY_SEPARATOR . PHP_SUBDIR . DIRECTORY_SEPARATOR;
  }
  include $phpDir . str_replace('\\', DIRECTORY_SEPARATOR, $className) . '.php';
});
