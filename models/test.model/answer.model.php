<?php
require_once(__DIR__ . "/../../core/active-record.core.php");

class Answer extends ActiveRecord {
    private string $type = "";
    private string $text = "";
    private int | null $id = null;

    public function getType(): string {
        return $this->type;
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
        $this->sync();

        if ($this->id === null) {
            $query = parent::$databaseObject->prepare("
                INSERT INTO Answer(type, text) 
                VALUES(:type, :text);
            ");
            $query->bindParam(':type', $this->type);
            $query->bindParam(':text', $this->text);
            return $query->execute();
        }

        $query = parent::$databaseObject->prepare("
                    UPDATE Answer 
                    SET type = :type, text = :text
                    WHERE id = :id;
            ");

        $query->bindParam(':type', $this->type);
        $query->bindParam(':text', $this->text);
        $query->bindParam(':id', $this->id);
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

    public static function findById(int $id): ActiveRecord | null {
        self::sync();
        $query = parent::$databaseObject->prepare("
                    SELECT * 
                    FROM Answer
                    WHERE id = :id;
            ");
        $query->bindParam(':id', $id);
        $query->execute();
        $resultSet = $query->fetch(PDO::FETCH_ASSOC);

        if (!$resultSet || count($resultSet) < 1)
            return null;

        $newObject = new Answer();
        $newObject->setId($resultSet["id"]);
        $newObject->setType($resultSet["type"]);
        $newObject->setText($resultSet["text"]);

        return $newObject;
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
            $objects[] = $newObject;
        }
        return $objects;
    }

    private static function sync() {
        $query = parent::$databaseObject->prepare("
                    CREATE TABLE IF NOT EXISTS Answer(
                        id INTEGER PRIMARY KEY AUTO_INCREMENT,
                        type VARCHAR(15) NOT NULL CHECK (type IN ('RIGHT', 'WRONG', 'ACTUAL')),
                        text VARCHAR(50)
                    );");
        $query->execute();
    }
}