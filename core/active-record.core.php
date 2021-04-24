<?php

abstract class ActiveRecord {
    private static PDO $databaseObject;

    public abstract function save(): bool;
    public abstract function delete(): bool;
    public abstract static function findById(int $id): ActiveRecord | null;
    public abstract static function findAll(): array;
}