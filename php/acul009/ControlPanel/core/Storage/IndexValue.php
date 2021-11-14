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
    private string $type;

    protected function __construct(string $indexName, int|float|string|bool $indexValue, bool $unique, string $type) {
        $this->indexName = $indexName;
        $this->indexValue = $indexValue;
        $this->unique = $unique;
        $this->type = $type;
    }

    public static function create(string $indexName, mixed $indexValue, bool $unique): static {
        if (is_array($indexValue) || is_object($indexValue)) {
            $indexValue = serialize($indexValue);
        }
        return new self($indexName, $indexValue, $unique, gettype($indexValue));
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
