<?php require_once('person.validator.php');
require_once(__DIR__ . "/../../models/test.model/test-results.model.php");

class ExamineeValidator extends PersonValidator {
    private $formData;

    public function __construct($formData) {
        parent::__construct($formData);
        $this->formData = $formData;

        $this->validators["sender-age"] = isset($formData["sender-age"]) && $this->isValidAge($formData["sender-age"]);
        $this->errors["sender-age"] = "Неверный возраст";

        $this->validators["sender-group"] = isset($formData["sender-group"]) && $this->isValidGroupName($formData["sender-group"]);
        $this->errors["sender-group"] = "Неверный номер группы";
    }

    public function validate(): array {
        $msg = "";
        $valid = true;

        foreach ($this->validators as $field => $validator) {
            if (!$validator) {
                $valid = false;
                $msg .= "{$this->errors[$field]}<br>";
            }
        }

        if ($valid)
            return [true, $this->verifyResults()];
        return [false, "$msg"];
    }

    public function isValidGroupName($groupName): bool {
        $validGroupNamePattern = '/^[А-Я]+\/[бма]-\d\d-\d-[оз]+$/u';
        return preg_match($validGroupNamePattern, $groupName);
    }

    public function isValidAge($age): bool {
        return is_numeric($age) && $age > 16 && $age < 150;
    }

    public function verifyResults(): TestResults {
        $student = new Student();
        $test = $student->getTest();
        $questions = $test->getTestQuestions();
        $testResult = new TestResults();

        foreach ($questions as $question) {
            if (!isset($this->formData[str_replace(' ', '_', $question->getQuestion())]))
                $newAnswer = new TestAnswer($question, []);
            else
                $newAnswer = new TestAnswer($question, $this->formData[str_replace(' ', '_', $question->getQuestion())]);
            $testResult->addAnswer($newAnswer);
        }
        $actualScore = $testResult->getActualScore();
        $maxScore = $testResult->getMaxScore();

        $percent = $actualScore / $maxScore * 100;
        return $testResult;
    }
}