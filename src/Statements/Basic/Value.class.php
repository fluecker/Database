<?php
namespace Database\Statements\Basic;


use Database\Functions\DatabaseFunctions;

class Value
{
    private $_value = '';

    public function __construct($value){
        $this->_value = DatabaseFunctions::real_escape_string($value);

        if(!is_numeric($this->_value)){
            $this->_value = (DatabaseFunctions::allowedMysqlFunction($this->_value) ? $this->_value : DatabaseFunctions::quoteString($this->_value));
        }
    }

    public function toSql(): string {
        return $this->_value;
    }
}