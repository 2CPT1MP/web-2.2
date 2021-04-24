<?php
require_once(__DIR__ . "/../../core/active-record.core.php");

class TestQuestion extends ActiveRecord {
    private int | null $id = null;
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

    public function setQuestion(string $question): string {
        return $this->question = $question;
    }

    public function getType(): string {
        return $this->type;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setType(string $type): string {
        return $this->type = $type;
    }

    public function save(): bool {
        $this->sync();

        if ($this->id === null) {
            $query = parent::$databaseObject->prepare("
                INSERT INTO TestQuestion(type, question) 
                VALUES(:type, :question);
            ");
            //$query->bindParam(':table', self::$TABLE_NAME);
            $query->bindParam(':type', $this->type);
            $query->bindParam(':question', $this->question);
            return $query->execute();
        }

        $query = parent::$databaseObject->prepare("
                    UPDATE TestQuestion 
                    SET type = :type, text = :text
                    WHERE id = :id;
            ");
        //$query->bindParam(':TableName', self::$TABLE_NAME);
        $query->bindParam(':type', $this->type);
        $query->bindParam(':question', $this->question);
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    public function delete(): bool {
        self::sync();
        if ($this->id === null)
            return false;

        $query = parent::$databaseObject->prepare("
                    DELETE FROM TestQuestion
                    WHERE id = :id;
            ");
       // $query->bindParam(':TableName', self::$TABLE_NAME);
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    public static function findById(int $id): ActiveRecord | null {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM TestQuestion
                    WHERE id = :id;
            ");
       // $query->bindParam(':TableName', self::$TABLE_NAME);
        $query->bindParam(':id', $id);
        $query->execute();
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);

        if (!$resultSet || count($resultSet) < 1)
            return null;

        $newObject = new TestQuestion($resultSet["question"]);
        $newObject->setId($resultSet["id"]);
        $newObject->setType($resultSet["type"]);

        return $newObject;
    }

    public static function findAll(): array {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM TestQuestion;
            ");
        //$query->bindParam(':TableName', self::$TABLE_NAME);
        $query->execute();
        $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultSet) < 1)
            return [];

        $objects = [];
        foreach ($resultSet as $row) {
            $newObject = new TestQuestion($row["question"]);
            $newObject->setId($row["id"]);
            $newObject->setType($row["type"]);
            $objects[] = $newObject;
        }
        return $objects;
    }

    private static function sync() {
        $query = parent::$databaseObject->prepare("
                    CREATE TABLE IF NOT EXISTS TestQuestion(
                        id INTEGER PRIMARY KEY AUTO_INCREMENT,
                        type VARCHAR(50) NOT NULL CHECK 
                            (type IN ('SINGLE_SELECT', 'MULTIPLE_SELECT', 'TEXT', 'RADIO')),
                        question VARCHAR(1000));
                    ");
        //$query->bindParam(':TableName', self::$TABLE_NAME);
        $query->execute();
    }
}