<?php
require_once('test-question.model.php');

class TestAnswer {
    private TestQuestion $testQuestion;
    private array $answers = [];

    public function __construct(TestQuestion $question, array $answers) {
        $this->testQuestion = $question;
        $this->answers = $answers;
    }

    public function getTestQuestion(): TestQuestion {
        return $this->testQuestion;
    }

    public function getAnswers(): array {
        return $this->answers;
    }

    public function getMaxScore(): int {
        return count($this->testQuestion->getRightAnswers());
    }

    public function getActualScore(): int {
        $score = 0;

        foreach ($this->testQuestion->getRightAnswers() as $rightAnswer) {
            foreach ($this->answers as $actualAnswer)
                if ($rightAnswer === $actualAnswer)
                    $score++;
        }
        return $score;
    }
}