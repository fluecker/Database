<?php
namespace Database\Statements;

use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Separator;

class Group extends Statement_Abstract {

    private $_column = [];
    private $_hasHaving = false;
    private $_having = null;
    private $_separator = null;

    public function __construct(array $columns = null){
        if($columns !== null){
            $this->setColumns($columns);
        }
        $this->_separator = new Separator(',');
    }

    public function setColumns(array $columns){
        foreach($columns as $coloumn){
            $this->_column[] = new Field($coloumn);
        }
    }

    public function setHaving(string $column, string $option, $value){
        $this->_hasHaving = true;
        $this->_having = new Having($column, $option, $value);
    }

    public function toSql(): string{
        $statement = '';

        if(count($this->_column) > 0) {
            $statement .= 'GROUP BY ';
            foreach ($this->_column as $column) {
                $statement .= $column->toSql() . $this->_separator->toSql();
            }

            $statement = substr($statement, 0, -2);

            if($this->_hasHaving){
                $statement .= ' ' . $this->_having->toSql();
            }
        }

        return $statement;
    }

}