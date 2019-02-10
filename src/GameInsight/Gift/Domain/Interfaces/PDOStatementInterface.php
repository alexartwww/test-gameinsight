<?php

namespace GameInsight\Gift\Domain\Interfaces;

interface PDOStatementInterface extends \Traversable
{
    public function bindColumn($column, &$param, int $type, int $maxlen, $driverdata): bool;

    public function bindParam($parameter, &$variable, int $data_type, int $length, $driver_options): bool;

    public function bindValue($parameter, $value, int $data_type = \PDO::PARAM_STR): bool;

    public function closeCursor(): bool;

    public function columnCount(): int;

    public function debugDumpParams();

    public function errorCode(): string;

    public function errorInfo(): array;

    public function execute(array $input_parameters): bool;

    public function fetch(int $fetch_style, int $cursor_orientation = \PDO::FETCH_ORI_NEXT, int $cursor_offset = 0);

    public function fetchAll(int $fetch_style, $fetch_argument, array $ctor_args = array()): array;

    public function fetchColumn(int $column_number = 0);

    public function fetchObject(string $class_name, array $ctor_args);

    public function getAttribute(int $attribute);

    public function getColumnMeta(int $column): array;

    public function nextRowset(): bool;

    public function rowCount(): int;

    public function setAttribute(int $attribute, $value): bool;

    public function setFetchMode(int $mode): bool;
}
