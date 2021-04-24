<?php
require_once("test-answer.model.php");
require_once(__DIR__ . "/../../core/active-record.core.php");

class TestResults extends ActiveRecord {
    private int $id;
    private array $testAnswer = [];

    public function addAnswer(TestAnswer $answer) {
        $this->testAnswer[] = $answer;
    }

    public function save(): bool {
        // TODO: Implement save() method.
        return false;
    }

    public function delete(): bool {
        // TODO: Implement delete() method.
        return false;
    }

    public static function findById(int $id): ActiveRecord | null {
        // TODO: Implement findById() method.
        return null;
    }

    public static function findAll(): array {
        // TODO: Implement findAll() method.
        return [];
    }

    public function getTestAnswer(): array {
        return $this->testAnswer;
    }

    public function getMaxScore(): int {
        $maxScore = 0;
        foreach ($this->testAnswer as $answer)
            $maxScore += $answer->getMaxScore();
        return $maxScore;
    }

    public function getActualScore(): int {
        $actualScore = 0;
        foreach ($this->testAnswer as $answer)
            $actualScore += $answer->getActualScore();
        return $actualScore;
    }
}