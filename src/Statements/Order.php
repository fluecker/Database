<?php
namespace Database\Statements;

use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Field;

class Order extends Statement_Abstract {
    protected $_column = [];

    public function __construct(array $columns = null){
        if($columns !== null){
            $this->setOrder($columns);
        }
    }

    public function setOrder(array $columns){
        foreach($columns as $column){

            $direction = 'ASC';

            if(isset($column[1]) && ($column[1] === 'ASC' || $column[1] === 'DESC')){
                $direction = $column[1];
            }

            $this->_column[$direction] = new Field($column[0]);
        }
    }

    public function toSql(): string{
        $statement = '';

        if(count($this->_column) > 0) {
            $statement .= "ORDER BY ";
            foreach ($this->_column as $direction => $column) {
                    $statement .= $column->toSql() . ' ' . $direction . ', ';
            }

            $statement = substr($statement, 0, -2);
        }
        return $statement;
    }
}
