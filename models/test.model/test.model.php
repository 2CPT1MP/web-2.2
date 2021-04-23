<?php require_once('test-question.model.php');

class Test {
    private array $testQuestions = [];

    public function addTestQuestion(TestQuestion $testQuestion): void {
        $this->testQuestions[] = $testQuestion;
    }

    public function getTestQuestions(): array {
        return $this->testQuestions;
    }
}