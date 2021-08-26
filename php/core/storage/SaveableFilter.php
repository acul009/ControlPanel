<?php

declare(strict_types=1);

namespace core\storage;

/**
 * Objects of this class are used as a syntax free way to add filters to db requests.
 * They are used by the driver to load the neccesary data.
 *
 * @author acul
 */
class SaveableFilter {

    const OPERATOR_OR = 0;
    const OPERATOR_AND = 1;
    const OPERATOR_EQUALS = 2;
    const OPERATOR_NOT_EQUALS = 3;
    const OPERATOR_LESS_THAN = 4;
    const OPERATOR_GREATER_THAN = 5;
    const OPERATOR_IN = 6;
    const OPERATOR_NOT_IN = 6;
    const OPERATOR_NOT = 7;

    private int $operator;
    private self|string $value1;
    private mixed $value2;

    private function __construct(self|string $value1, int $operator, mixed $value2) {
        $this->operator = $operator;
        $this->value1 = $value1;
        $this->value2 = $value2;
    }

    public function getValue1(): self|string {
        return $this->value1;
    }

    public function getValue2(): mixed {
        return $this->value2;
    }

    public function getOperator(): int {
        return $this->operator;
    }

    public function or(self $filter): self {
        return new self($this, self::OPERATOR_OR, $filter);
    }

    public function and(self $filter): self {
        return new self($this, self::OPERATOR_AND, $filter);
    }

    public function not(): self {
        return new self($this, self::OPERATOR_NOT);
    }

    public static function equals(string $indexName, mixed $indexValue) {
        return new self($indexName, self::OPERATOR_EQUALS, $indexValue);
    }

    public static function notEquals(string $indexName, mixed $indexValue) {
        return new self($indexName, self::OPERATOR_NOT_EQUALS, $indexValue);
    }

    public static function less(string $indexName, mixed $indexValue) {
        return new self($indexName, self::OPERATOR_LESS_THAN, $indexValue);
    }

    public static function greater(string $indexName, mixed $indexValue) {
        return new self($indexName, self::OPERATOR_GREATER_THAN, $indexValue);
    }

    public static function in(string $indexName, mixed $indexValue) {
        return new self($indexName, self::OPERATOR_IN, $indexValue);
    }

    public static function notIn(string $indexName, mixed $indexValue) {
        return new self($indexName, self::OPERATOR_NOT_IN, $indexValue);
    }

}
