<?php

declare(strict_types=1);

namespace acul009\ControlPanel\core\Storage\Saveable;

use acul009\ControlPanel\core\Storage\IndexValue;
use acul009\ControlPanel\core\Storage\IndexCollection;

/**
 * Description of SavableTest
 *
 * @author acul
 */
class Test extends SaveableObject {

    private string $testValue = 'lel';
    private string $testValue2 = 'this is a test';
    public int $intValue = 7;
    private int $intValue3;
    private int $intValue2;
    private string $testString;

    public function __construct() {
        $this->intValue3 = &$this->intValue;
    }

    public function getTestString(): string {
        return $this->testString;
    }

    public function setTestString(string $testString): void {
        $this->testString = $testString;
    }

    protected function generateIndices(): ?IndexCollection {
        $objTestIndex = IndexValue::create('test', $this->testString, true);
        return null;
    }

}
