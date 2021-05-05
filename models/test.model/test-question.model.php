<?php
require_once(__DIR__ . "/../../core/active-record.core.php");
require_once("test.model.php");

class TestQuestion extends ActiveRecord {
    private int | null $id = null;
    private int | null $testId = null;
    private string $question;
    private string $type;
    private array $rightAnswers = [];
    private array $wrongAnswers = [];

    public function getRightAnswers(): array { return $this->rightAnswers; }
    public function getWrongAnswers(): array { return $this->wrongAnswers; }

    public function addRightAnswer(Answer $answer): void { $this->rightAnswers[] = $answer; }
    public function addWrongAnswer(Answer $answer) { $this->wrongAnswers[] = $answer; }

    public function getQuestion(): string { return $this->question; }
    public function setQuestion(string $question): string { return $this->question = $question; }

    public function getType(): string { return $this->type; }
    public function setType(string $type): string { return $this->type = $type; }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function getTestId(): ?int { return $this->testId; }
    public function setTestId(?int $testId): void { $this->testId = $testId; }


    public function __construct(string $question, string $type='SINGLE_SELECT') {
        $this->type = $type;
        $this->question = $question;
    }

    private function saveAnswers() {
        foreach ($this->wrongAnswers as $wrongAnswer) {
            $wrongAnswer->setTestQuestionId($this->id);
            $wrongAnswer->save();
        }
        foreach ($this->rightAnswers as $rightAnswer) {
            $rightAnswer->setTestQuestionId($this->id);
            $rightAnswer->save();
        }
    }

    public function save(): bool {
        $this->sync();

        if ($this->id === null) {
            $query = parent::$databaseObject->prepare("
                INSERT INTO TestQuestion(type, question, testId) 
                VALUES(:type, :question, :testId);
            ");
            $query->bindParam(':type', $this->type);
            $query->bindParam(':question', $this->question);
            $query->bindParam(':testId', $this->testId);
            $res = $query->execute();
            $this->setId(parent::$databaseObject->lastInsertId());
            $this->saveAnswers();
            return $res;
        }

        $query = parent::$databaseObject->prepare("
                    UPDATE TestQuestion 
                    SET type = :type, question = :question, testId = :testId
                    WHERE id = :id;
            ");
        $query->bindParam(':type', $this->type);
        $query->bindParam(':question', $this->question);
        $query->bindParam(':id', $this->id);
        $query->bindParam(':testId', $this->testId);
        return $query->execute();
    }

    public static function findAll(): array {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM TestQuestion;
            ");
        $query->execute();
        $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultSet) < 1)
            return [];

        $objects = [];
        foreach ($resultSet as $row) {
            $newObject = new TestQuestion($row["question"]);
            $newObject->setId($row["id"]);
            $newObject->setTestId($row["testId"]);
            $newObject->setType($row["type"]);
            $newObject->findAllAnswers();
            $objects[] = $newObject;
        }
        return $objects;
    }

    public function delete(): bool {
        self::sync();
        if ($this->id === null)
            return false;

        $query = parent::$databaseObject->prepare("
                    DELETE FROM TestQuestion
                    WHERE id = :id;
            ");
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    private static function findBy(string $fieldName, $value, bool $fetchAll = false): ActiveRecord | array | null {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM TestQuestion
                    WHERE $fieldName = :value;
            ");
        $query->bindParam(':value', $value);
        $query->execute();

        if ($fetchAll) {
            $objects = [];
            $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($resultSet as $row) {
                $newObject = new TestQuestion($row["question"]);
                $newObject->setId($row["id"]);
                $newObject->setTestId($row["testId"]);
                $newObject->setType($row["type"]);
                $newObject->findAllAnswers();
                $objects[] = $newObject;
            }
            return $objects;
        }
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);

        if (!$resultSet || count($resultSet) < 1)
            return null;

        $newObject = new TestQuestion($resultSet["question"]);
        $newObject->setId($resultSet["id"]);
        $newObject->setType($resultSet["type"]);

        return $newObject;
    }

    public static function findById(int $id): ActiveRecord | null {
        return self::findBy("id", $id);
    }

    public static function findAllByTestId(int $testId): array {
        return self::findBy("testId", $testId, true);
    }

    public function findAllAnswers(): void {
        if ($this->id === null)
            return;
        $answers = Answer::findAllByQuestionId($this->id);

        foreach ($answers as $answer) {
            switch ($answer->getType()) {
                case "RIGHT":
                    $this->rightAnswers[] = $answer;
                    break;
                case "WRONG":
                    $this->wrongAnswers[] = $answer;
                    break;
            }
        }
    }

    public static function sync() {
        Test::sync();
        $query = parent::$databaseObject->prepare("
                    CREATE TABLE IF NOT EXISTS TestQuestion(
                        id INTEGER PRIMARY KEY AUTO_INCREMENT,
                        type VARCHAR(50) NOT NULL CHECK 
                            (type IN ('SINGLE_SELECT', 'MULTIPLE_SELECT', 'TEXT', 'RADIO')),
                        question VARCHAR(1000),
                        testId INTEGER,
                        FOREIGN KEY (testId) REFERENCES Test(id) ON DELETE CASCADE );
                    ");
        $query->execute();
    }
}