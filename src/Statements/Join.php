<?php
namespace Database\Statements;


use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Separator;
use Database\Statements\Where\Functions\ColumnStatement;

class Join extends Statement_Abstract {
    protected $_field = null;
    protected $_collection = [];

    private $_start = 'JOIN';
    private $_connection = null;
    private $_separator = null;

    public function __construct(array $field, array $columns){
        $this->_field = new Field($field[0], (isset($field[1]) ? $field[1] : null));

        if(count($columns) == 2 && !is_array($columns[0])){
            $this->_collection[] = new ColumnStatement($columns);
        } else {
            foreach ($columns as $column) {
                $this->_collection[] = new ColumnStatement($column);
            }
        }

        $this->_separator = new Separator('=');
        $this->_connection = new Separator('ON');
    }

    public function toSql(): string{

        $on = '';
        foreach($this->_collection as $collection){
            $on .= $collection->toSql() . ', ';
        }

        $on = substr($on, 0, -2);

        return $this->_start . $this->_field->toSql() . $this->_connection->toSql() . $on;
    }
}