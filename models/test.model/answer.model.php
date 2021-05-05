<?php
require_once(__DIR__ . "/../../core/active-record.core.php");
require_once(__DIR__ . "/../../models/test.model/result.model.php");

class Answer extends ActiveRecord {
    private string $type = "";
    private string $text = "";
    private int | null $id = null, $testQuestionId = null, $resultId = null;

    public function getType(): string {
        return $this->type;
    }

    public function getResultId(): ?int { return $this->resultId; }

    public function setResultId(?int $resultId): void { $this->resultId = $resultId; }

    public function __construct(string $text = "", string $type = "RIGHT") {
        $this->text = $text;
        $this->type = $type;
    }

    public function setTestQuestionId(int | null $testQuestionId): void {
        $this->testQuestionId = $testQuestionId;
    }

    public function getTestQuestionId(): ?int {
        return $this->testQuestionId;
    }

    public function setType(string $type): void {
        $this->type = $type;
    }

    public function getText(): string {
        return $this->text;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(?int $id): void {
        $this->id = $id;
    }

    public function setText(string $text): void {
        $this->text = $text;
    }

    public function save(): bool {
        self::sync();

        if ($this->id === null) {
            $query = parent::$databaseObject->prepare("
                INSERT INTO Answer(type, text, testQuestionId, resultId) 
                VALUES(:type, :text, :testQuestionId, :resultId);
            ");
            $query->bindParam(':type', $this->type);
            $query->bindParam(':text', $this->text);
            $query->bindParam(':testQuestionId', $this->testQuestionId);
            $query->bindParam(':resultId', $this->resultId);
            $res = $query->execute();
            //var_dump(parent::$databaseObject->lastInsertId());
            $this->setId(parent::$databaseObject->lastInsertId());
            return $res;
        }

        $query = parent::$databaseObject->prepare("
                    UPDATE Answer 
                    SET type = :type, text = :text, testQuestionId = :testQuestionId, resultId = :resultId
                    WHERE id = :id;
            ");

        $query->bindParam(':type', $this->type);
        $query->bindParam(':text', $this->text);
        $query->bindParam(':id', $this->id);
        $query->bindParam(':testQuestionId', $this->testQuestionId);
        $query->bindParam(':resultId', $this->resultId);
        return $query->execute();
    }

    public function delete(): bool {
        self::sync();
        if ($this->id === null)
            return false;

        $query = parent::$databaseObject->prepare("
                    DELETE FROM Answer
                    WHERE id = :id;
            ");
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    private static function findBy(string $fieldName, $value, bool $fetchAll = false): ActiveRecord | array | null {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM Answer
                    WHERE $fieldName = :value;
            ");
        $query->bindParam(':value', $value);
        $query->execute();

        if ($fetchAll) {
            $objects = [];
            $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($resultSet as $row) {
                $newObject = new Answer();
                $newObject->setId($row["id"]);
                $newObject->setType($row["type"]);
                $newObject->setText($row["text"]);
                $newObject->setTestQuestionId($row["testQuestionId"]);
                $newObject->setResultId($row["resultId"]);
                $objects[] = $newObject;
            }
            return $objects;
        }
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);

        if (!$resultSet || count($resultSet) < 1)
            return null;

        $newObject = new Answer();
        $newObject->setId($resultSet["id"]);
        $newObject->setType($resultSet["type"]);
        $newObject->setText($resultSet["text"]);
        $newObject->setTestQuestionId($resultSet["testQuestionId"]);
        $newObject->setResultId($resultSet["resultId"]);

        return $newObject;
    }

    public static function findById(int $id): ActiveRecord | null {
        return self::findBy("id", $id);
    }

    public static function findAllByQuestionId(int $questionId): array {
        return self::findBy("testQuestionId", $questionId, true);
    }

    public static function findAllByResultId(int $resultId): array {
        return self::findBy("resultId", $resultId, true);
    }

    public static function findAll(): array {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM Answer;
            ");
        $query->execute();
        $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultSet) < 1)
            return [];

        $objects = [];
        foreach ($resultSet as $row) {
            $newObject = new Answer();
            $newObject->setId($row["id"]);
            $newObject->setType($row["type"]);
            $newObject->setText($row["text"]);
            $newObject->setTestQuestionId($row["testQuestionId"]);
            $newObject->setResultId($row["resultId"]);
            $objects[] = $newObject;
        }
        return $objects;
    }

    private static function sync() {
        TestQuestion::sync();
        Result::sync();
        $query = parent::$databaseObject->prepare("
                    CREATE TABLE IF NOT EXISTS Answer(
                        id INTEGER PRIMARY KEY AUTO_INCREMENT,
                        type VARCHAR(15) NOT NULL CHECK (type IN ('RIGHT', 'WRONG', 'ACTUAL')),
                        text VARCHAR(50),
                        testQuestionId INTEGER NOT NULL,
                        resultId INTEGER,
                        FOREIGN KEY (testQuestionId) REFERENCES TestQuestion(id) ON DELETE CASCADE,
                        FOREIGN KEY (resultId) REFERENCES Result(id) ON DELETE CASCADE
                         );
                    ");
        $query->execute();
    }
}