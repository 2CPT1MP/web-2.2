<?php

abstract class ActiveRecord {
    protected static PDO $databaseObject;

    public abstract function save(): bool;
    public abstract function delete(): bool;
    public abstract static function findById(int $id): ActiveRecord | null;
    public abstract static function findAll(): array;

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
            echo 'Подключение не удалось: ' . $e->getMessage();
        }
    }
}