<?php require_once('person.validator.php');

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
            return [true, "$msg {$this->verifyResults()}"];
        return [false, "$msg"];
    }

    public function isValidGroupName($groupName): bool {
        $validGroupNamePattern = '/^[А-Я]+\/[бма]-\d\d-\d-[оз]+$/u';
        return preg_match($validGroupNamePattern, $groupName);
    }

    public function isValidAge($age): bool {
        return is_numeric($age) && $age > 16 && $age < 150;
    }

    public function verifyResults(): string {
        $student = new Student();
        $test = $student->getTest();
        $questions = $test->getTestQuestions();

        $score = 0;
        $total = 0;

        foreach ($questions as $q)
            $total += count($q->getRightAnswers());

        foreach ($questions as $question) {
            $rightAnswers = $question->getRightAnswers();

            if (!isset($this->formData[str_replace(' ', '_', $question->getQuestion())]))
                continue;

            foreach ($this->formData[str_replace(' ', '_', $question->getQuestion())] as $answer)
                foreach ($rightAnswers as $answ)
                    if ($answ === $answer)
                        $score++;
        }
        $percent = $score / $total * 100;
        return "<p>Правильных ответов {$score} из {$total} ($percent%)</p>";
    }
}