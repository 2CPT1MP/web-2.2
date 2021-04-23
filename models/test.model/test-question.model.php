<?php

class TestQuestion {
    private string $question;
    private string $type;
    private array $rightAnswers = [];
    private array $wrongAnswers = [];

    public function getRightAnswers(): array {
        return $this->rightAnswers;
    }

    public function getWrongAnswers(): array {
        return $this->wrongAnswers;
    }

    public function __construct(string $question, string $type='SINGLE_SELECT') {
        $this->type = $type;
        $this->question = $question;
    }

    public function addRightAnswer(string $answer): void {
        $this->rightAnswers[] = $answer;
    }

    public function addWrongAnswer(string $answer) {
        $this->wrongAnswers[] = $answer;
    }

    public function getQuestion(): string {
        return $this->question;
    }

    public function getType(): string {
        return $this->type;
    }
}