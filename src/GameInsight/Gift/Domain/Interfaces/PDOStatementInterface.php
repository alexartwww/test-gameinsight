<?php

namespace GameInsight\Gift\Domain\Interfaces;

/**
 * Interface PDOStatementInterface
 * @package GameInsight\Gift\Domain\Interfaces
 */
interface PDOStatementInterface extends \Traversable
{
    /**
     * @param $column
     * @param $param
     * @param int $type
     * @param int $maxlen
     * @param $driverdata
     * @return bool
     */
    public function bindColumn($column, &$param, int $type, int $maxlen, $driverdata): bool;

    /**
     * @param $parameter
     * @param $variable
     * @param int $data_type
     * @param int $length
     * @param $driver_options
     * @return bool
     */
    public function bindParam($parameter, &$variable, int $data_type, int $length, $driver_options): bool;

    /**
     * @param $parameter
     * @param $value
     * @param int $data_type
     * @return bool
     */
    public function bindValue($parameter, $value, int $data_type = \PDO::PARAM_STR): bool;

    /**
     * @return bool
     */
    public function closeCursor(): bool;

    /**
     * @return int
     */
    public function columnCount(): int;

    /**
     * @return mixed
     */
    public function debugDumpParams();

    /**
     * @return string
     */
    public function errorCode(): string;

    /**
     * @return array
     */
    public function errorInfo(): array;

    /**
     * @param array $input_parameters
     * @return bool
     */
    public function execute(array $input_parameters): bool;

    /**
     * @param int $fetch_style
     * @param int $cursor_orientation
     * @param int $cursor_offset
     * @return mixed
     */
    public function fetch(int $fetch_style, int $cursor_orientation = \PDO::FETCH_ORI_NEXT, int $cursor_offset = 0);

    /**
     * @param int $fetch_style
     * @param $fetch_argument
     * @param array $ctor_args
     * @return array
     */
    public function fetchAll(int $fetch_style, $fetch_argument, array $ctor_args = array()): array;

    /**
     * @param int $column_number
     * @return mixed
     */
    public function fetchColumn(int $column_number = 0);

    /**
     * @param string $class_name
     * @param array $ctor_args
     * @return mixed
     */
    public function fetchObject(string $class_name, array $ctor_args);

    /**
     * @param int $attribute
     * @return mixed
     */
    public function getAttribute(int $attribute);

    /**
     * @param int $column
     * @return array
     */
    public function getColumnMeta(int $column): array;

    /**
     * @return bool
     */
    public function nextRowset(): bool;

    /**
     * @return int
     */
    public function rowCount(): int;

    /**
     * @param int $attribute
     * @param $value
     * @return bool
     */
    public function setAttribute(int $attribute, $value): bool;

    /**
     * @param int $mode
     * @return bool
     */
    public function setFetchMode(int $mode): bool;
}
