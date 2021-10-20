<?php

namespace acul009\ControlPanel\core\Storage;

/**
 * Description of IndexValue
 *
 * @author acul
 */
class IndexValue {

    private string $indexName;
    private int|float|string|bool $indexValue;
    private bool $unique;

    protected function __construct(string $indexName, int|float|string|bool $indexValue, bool $unique = false) {
        $this->indexName = $indexName;
        $this->indexValue = $indexValue;
        $this->unique = $unique;
    }

    public static function create(string $indexName, mixed $indexValue, bool $unique = false): static {
        if (is_array($indexValue) || is_object($indexValue)) {
            $indexValue = serialize($indexValue);
        }
        return new self($indexName, $indexValue, $unique);
    }

    public function getIndexName(): string {
        return $this->indexName;
    }

    public function getIndexValue(): int|float|string|bool {
        return $this->indexValue;
    }

    public function getUnique(): bool {
        return $this->unique;
    }

}
