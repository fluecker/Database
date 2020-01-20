<?php
namespace Config;

use AbstractClasses\Connection;

class LogConnection extends Connection {

    private $_log_table_name = '';
    private $_log_table_columns = [];
    private $_log_table_values = [];

    /**
     * @param string $log_table_name
     */
    public function setLogTableName(string $log_table_name): void
    {
        $this->_log_table_name = $log_table_name;
    }

    /**
     * @param array $log_table_columns
     */
    public function setLogTableColumns(array $log_table_columns): void
    {
        $this->_log_table_columns = $log_table_columns;
    }

    /**
     * @return string
     */
    public function getLogTableName(): string {
        return $this->_log_table_name;
    }

    /**
     * @return array
     */
    public function getLogTableColumns(): array {
        return $this->_log_table_columns;
    }

    /**
     * @return array
     */
    public function getLogTableValues(): array {
        return $this->_log_table_values;
    }

    /**
     * @param array $value
     */
    public function setLogTableValues($value): void {
        $this->_log_table_values = $value;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function setLogTableValue(string $key, $value): void {
        $this->_log_table_values[$key] = $value;
    }
}