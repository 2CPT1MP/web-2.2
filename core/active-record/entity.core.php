<?php interface IEntity {
    function save(): bool;
    function delete(): bool;
    static function find(Filter $filter, bool $fetchAll = true);
    static function findAll(): array;
}