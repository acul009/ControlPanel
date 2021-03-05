<?php

namespace core;

/**
 * Description of ProtectedSingleton
 *
 * @author acul
 */
abstract class ProtectedSingleton {

  private static bool $created = false;

  protected final function __construct() {

  }

  abstract protected function init(): void;

  public static final function create(): self {
    if (self::$created) {
      throw new ProtectedSingletonException();
    }
    self::$created = true;
    $args = func_get_args();
    $instance = new static(...$args);
    $instance->init();
    return $instance;
  }

}
