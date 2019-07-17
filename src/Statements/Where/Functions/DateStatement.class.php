<?php
namespace Database\Statements\Where\Functions;

use Database\Statements\Basic\AddDate;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Interval;
use Database\Statements\Basic\Separator;

class DateStatement
{
    private $_field = null;
    private $_date = null;
    private $_interval = null;
    private $_separator = null;

    public function __construct(string $field, string $datefield, string $interval, string $separator = null) {
        $this->_field = new Field($field);
        $this->_date = new AddDate($datefield, $interval);
        $this->_separator = new Separator($separator);
    }

    public function toSql(){
        return $this->_date->toSql() . $this->_separator->toSql() . $this->_field->toSql();
    }
}