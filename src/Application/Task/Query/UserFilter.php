<?php

declare(strict_types=1);

namespace App\Application\Task\Query;

class UserFilter
{
    private int $offset;
    private int $limit;
    private array $fields = [];

    public function __construct()
    {
        $this->offset = 0;
        $this->limit = 10;
    }

    public function setOffset(int $offset): UserFilter
    {
        $this->offset = $offset;

        return $this;
    }

    public function setLimit(int $limit): UserFilter
    {
        $this->limit = $limit;

        return $this;
    }

    public function setFieldCondition(string $fieldName, $value): UserFilter
    {
        $this->fields[$fieldName] = $value;

        return $this;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}
