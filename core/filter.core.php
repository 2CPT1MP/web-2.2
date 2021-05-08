<?php

class Filter {
    private array $conditions = [];
    public function getConditions(): array { return $this->conditions; }

    public function addCondition(string $field, mixed $value): void {
        $this->conditions["$field"] = $value;
    }

    public function getSqlWhereCondition(): string {
        if (count($this->conditions) < 1)
            return "";

        $condition = "WHERE";
        $i = 1;

        foreach ($this->conditions as $field => $value) {
            $condition .= " $field = :$field";
            if ($i < count($this->conditions)) {
                $condition .= "AND";
                $i++;
            }
        }
        return $condition;
    }
}