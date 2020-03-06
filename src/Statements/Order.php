<?php
namespace Database\Statements;

use Database\AbstractClasses\Statement_Abstract;
use Database\Exceptions\DatabaseExceptions;
use Database\Statements\Basic\Field;
use Database\Statements\Fields\Fields;
use Statements\Cases\Cases;

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

    /**
     * @param mixed $_when
     * @param string|null $_field
     * @param string|null $_else
     * @param string $direction
     * @return $this|Cases
     * @throws DatabaseExceptions
     */
    public function addCase($_when = null, ?string $_field = null, ?string $_else = null, string $direction = 'ASC'){

        if($_when !== null || $_field !== null || $_else !== null) {
            $this->_column[$direction] = new Cases($_when, $_field, $_else);
            return $this;
        } else {
            $this->_column[$direction] = new Cases($_when, $_field, $_else);
            return end($this->_column);
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
