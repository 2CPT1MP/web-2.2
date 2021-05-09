<?php require_once("filter.core.php");

abstract class ActiveRecord {
    protected static PDO $databaseObject;
    public static function getDatabaseObject(): PDO { return self::$databaseObject; }

    public static function connect(): void {
        $dsn = 'mysql:dbname=student_db;host=127.0.0.1';
        $username = 'root';
        $password = '614729';
        try {
            self::$databaseObject = new PDO(
                $dsn, $username, $password,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (PDOException $e) {
            echo 'Cannot connect to the database' . $e->getMessage();
        }
    }

    public static function find(string $tableName,
                                $syncFunction,
                                $setterFunction,
                                Filter $filter,
                                bool $fetchAll): ActiveRecord | array | null {
        $syncFunction();

        $query = self::$databaseObject->prepare("
            SELECT * 
            FROM $tableName 
            {$filter->getSqlWhereCondition()};
        ");

        foreach ($filter->getConditions() as $field => $value)
            $query->bindValue(":$field", $value);
        $query->execute();

        $resultSet = $query->fetchAll(PDO::FETCH_ASSOC);
        if ($fetchAll) {
            $objects = [];
            foreach ($resultSet as $row)
                $objects[] = $setterFunction($row);
            return $objects;
        }

        if (!$resultSet || count($resultSet) < 1)
            return null;

        return $setterFunction($resultSet);
    }
}