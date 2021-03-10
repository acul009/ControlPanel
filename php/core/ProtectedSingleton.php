<?php

namespace core;

/**
 * The ProtectedSingleton is a parent class for singleton objects which should
 * be protected against other instances. This is important for security and consistency.
 * I'm still not sure how to actually deal with inheritance though...
 *
 * @author acul
 */
abstract class ProtectedSingleton {

  private static bool $created = false;

  protected final function __construct() {

  }

  /**
   * This function is used to initialize the object.
   * all arguments from create() are passed on.
   * <br><b>To make this implementation effective, the child class has to declare this method final!</b>
   */
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
