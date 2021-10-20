<?php

namespace acul009\ControlPanel\core\Storage;

/**
 * Description of IndexCollection
 *
 * @author acul
 */
class IndexCollection {

    private array $indices = [];

    public function addNewIndex(string $indexName, mixed $indexValue, bool $unique = false): void {
        $this->addIndex(IndexValue::create($indexName, $indexValue, $unique));
    }

    public function addIndex(IndexValue $index): void {
        $this->indices[] = $index;
    }

    public function getIndices(): array {
        return $this->indices;
    }

}
