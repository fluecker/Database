<?php
namespace Database\Statements\Where\Functions;


use Database\Statements\Basic\Field;

class IsNullStatement
{
    private $_beginn = 'IS';
    private $_not = 'NOT';
    private $_end = 'NULL';
    private $_field = null;
    private $_isNot = null;

    public function __construct($statement) {
        $this->_field = new Field($statement[0]);
        if(isset($statement[1])) {
            $this->_isNot = $statement[1];
        }
    }

    public function toSql(): string{
        return $this->_field->toSql() . $this->_beginn . ($this->_isNot ?  ' ' . $this->_not : '') . ' ' . $this->_end;
    }
}