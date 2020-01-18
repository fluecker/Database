<?php
namespace Database\Statements\Where\Functions;


use Database\Statements\Basic\Field;
use Database\Statements\Basic\Not;

class IsNullStatement
{
    private $_beginn = 'IS';
    private $_end = 'NULL';
    private $_field = null;
    private $_not = null;

    public function __construct($statement, $not = false) {
        $this->_field = new Field($statement);

        if($not){
            $this->_not = new Not();
        }
    }

    public function toSql(): string{
        return $this->_field->toSql() . $this->_beginn . ($this->_not !== null ?  ' ' . $this->_not->toSql() : '') . ' ' . $this->_end;
    }
}