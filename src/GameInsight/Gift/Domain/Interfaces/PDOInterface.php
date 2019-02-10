<?php

namespace GameInsight\Gift\Domain\Interfaces;

interface PDOInterface
{
    public function __construct(string $dsn, string $username, string $passwd, array $options);

    public function beginTransaction(): bool;

    public function commit(): bool;

    public function errorCode(): string;

    public function errorInfo(): array;

    public function exec(string $statement): int;

    public function getAttribute(int $attribute);

    public static function getAvailableDrivers(): array;

    public function inTransaction(): bool;

    public function lastInsertId(string $name = NULL): string;

    public function prepare(string $statement, array $driver_options = array());

    public function query(string $statement);

    public function quote(string $string, int $parameter_type = \PDO::PARAM_STR): string;

    public function rollBack(): bool;

    public function setAttribute(int $attribute, mixed $value): bool;
}
