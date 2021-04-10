<?php

namespace core\storage;

/**
 * Description of SavableTest
 *
 * @author acul
 */
class SavableTest extends SaveableObject {

  private string $testValue = 'lel';
  private string $testValue2 = 'this is a test';
  public int $intValue = 7;
  private int $intValue3;
  private int $intValue2;

  public function __construct() {
    $this->intValue3 = &$this->intValue;
  }

}
