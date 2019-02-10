<?php

namespace GameInsight\Gift\Domain\Interfaces;

/**
 * Interface PDOInterface
 * @package GameInsight\Gift\Domain\Interfaces
 */
interface PDOInterface
{
    /**
     * PDOInterface constructor.
     * @param string $dsn
     * @param string $username
     * @param string $passwd
     * @param array $options
     */
    public function __construct(string $dsn, string $username, string $passwd, array $options);

    /**
     * @return bool
     */
    public function beginTransaction(): bool;

    /**
     * @return bool
     */
    public function commit(): bool;

    /**
     * @return string
     */
    public function errorCode(): string;

    /**
     * @return array
     */
    public function errorInfo(): array;

    /**
     * @param string $statement
     * @return int
     */
    public function exec(string $statement): int;

    /**
     * @param int $attribute
     * @return mixed
     */
    public function getAttribute(int $attribute);

    /**
     * @return array
     */
    public static function getAvailableDrivers(): array;

    /**
     * @return bool
     */
    public function inTransaction(): bool;

    /**
     * @param string|NULL $name
     * @return string
     */
    public function lastInsertId(string $name = NULL): string;

    /**
     * @param string $statement
     * @param array $driver_options
     * @return mixed
     */
    public function prepare(string $statement, array $driver_options = array());

    /**
     * @param string $statement
     * @return mixed
     */
    public function query(string $statement);

    /**
     * @param string $string
     * @param int $parameter_type
     * @return string
     */
    public function quote(string $string, int $parameter_type = \PDO::PARAM_STR): string;

    /**
     * @return bool
     */
    public function rollBack(): bool;

    /**
     * @param int $attribute
     * @param mixed $value
     * @return bool
     */
    public function setAttribute(int $attribute, mixed $value): bool;
}
