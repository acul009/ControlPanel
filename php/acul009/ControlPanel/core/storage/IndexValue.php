<?php

namespace \acul009\ControlPanel\\acul009\ControlPanel\core\storage;

/**
 * Description of IndexValue
 *
 * @author acul
 */
class IndexValue {

    private string $indexName;
    private int|float|string|bool $indexValue;
    private bool $unique;

    public function __construct(string $indexName, int|float|string|bool $indexValue, bool $unique = false) {
        $this->indexName = $indexName;
        $this->indexValue = $indexValue;
        $this->unique = $unique;
    }

    public static function create(string $indexName, mixed $indexValue, bool $unique = false) {
        if (is_array($indexValue) || is_object($indexValue)) {
            $indexValue = serialize($indexValue);
        }
        return new self($indexName, $indexValue, $unique);
    }

}
