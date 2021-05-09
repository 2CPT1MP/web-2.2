<?php require_once('answer.model.php');
require_once(__DIR__ . "/../../core/active-record.core.php");

class Result extends ActiveRecord {
    private array $answers = [];
    private string | null $title;
    private int | null $id = null;

    public function getTitle(): string | null { return $this->title; }
    public function setTitle(string $title): void { $this->title = $title; }

    public function setAnswers(array $testQuestions): void {
        foreach ($testQuestions as $question) {
            $question->setResultId($this->id);
        }
        $this->answers = $testQuestions;
    }
    public function addAnswer(Answer $answer): void {
        $answer->setResultId($this->id);
        $this->answers[] = $answer;
    }
    public function getAnswers(): array { return $this->answers; }

    public function getId(): ?int { return $this->id; }
    public function setId(?int $id): void { $this->id = $id; }

    public function __construct(string $title) { $this->title = $title; }


    public function save(): bool {
        self::sync();

        if ($this->id === null) {
            $query = parent::$databaseObject->prepare("
                INSERT INTO Result(title) 
                VALUES(:title);
            ");
            $query->bindParam(':title', $this->title);
            $res = $query->execute();
            $this->setId(parent::$databaseObject->lastInsertId());

            $this->saveAnswers();
            return $res;
        }

        $query = parent::$databaseObject->prepare("
                    UPDATE Result 
                    SET title = :title
                    WHERE id = :id;
        ");

        $query->bindParam(':id', $this->id);
        $this->saveAnswers();
        return $query->execute();
    }

    private function saveAnswers() {
        foreach ($this->answers as $answer) {
            $answer->setResultId($this->id);
            $answer->save();
        }
    }

    public function delete(): bool {
        self::sync();
        if ($this->id === null)
            return false;

        $query = parent::$databaseObject->prepare("
                    DELETE FROM Result
                    WHERE id = :id;
        ");
        $query->bindParam(':id', $this->id);
        return $query->execute();
    }

    public static function findById(int $id): Result | null {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM Result
                    WHERE id = :id;
        ");
        $query->bindParam(':id', $id);
        $query->execute();
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);

        if (!$resultSet || count($resultSet) < 1)
            return null;

        $newObject = new Result($resultSet["title"]);
        $newObject->setId($resultSet["id"]);
        $newObject->setAnswers(Answer::findAllByResultId($newObject->id));

        return $newObject;
    }

    public static function findAll(): array {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM Result;
        ");
        $query->execute();
        $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);

        if (count($resultSet) < 1)
            return [];

        $objects = [];
        foreach ($resultSet as $row) {
            $newObject = new Result($row["title"]);
            $newObject->setId($row["id"]);
            $newObject->setAnswers(Answer::findAllByResultId($newObject->id));
            $objects[] = $newObject;
        }
        return $objects;
    }

    public static function sync() {
        $query = parent::$databaseObject->prepare("
                    CREATE TABLE IF NOT EXISTS Result(
                        id INTEGER PRIMARY KEY AUTO_INCREMENT,
                        title VARCHAR(250) NOT NULL);
                    ");
        $query->execute();
    }
}