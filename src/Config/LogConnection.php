<?php
namespace Config;

use AbstractClasses\Connection;

class LogConnection extends Connection {

    private $_log_table_name = '';
    private $_log_table_columns = [];
    private $_log_table_values = [];

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
     * @param string $key
     * @param $value
     */
    public function setLogTableValues(string $key, $value): void {
        $this->_log_table_values[$key] = $value;
    }
}