<?php

declare(strict_types=1);

namespace core\storage;

/**
 * Description of SaveableFilter
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
    private self $value1;
    private self $value2;

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

}
