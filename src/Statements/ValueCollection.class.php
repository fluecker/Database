<?php
namespace Database\Statements;

use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Value;

class ValueCollection extends Statement_Abstract {
    private $_values = [];

    public function __construct(array $values = null) {
        if($values !== null){
            $this->setValues($values);
        }
    }

    public function setValues(array $values){
        $tmpSingle = [];
        foreach($values as $value){
            if(is_array($value)){
                $tmpMulti = [];
                foreach($value as $val){
                    $tmpMulti[] = new Value($val);
                }
                $this->_values[] = $tmpMulti;
            } else {
                $tmpSingle[] = new Value($value);
            }
        }

        if(count($tmpSingle) > 0){
            $this->_values[] = $tmpSingle;
        }
    }

    public function toSql(): string {
        $statement = 'VALUES ';

        foreach($this->_values as $val){
            $statement .= '(';
            foreach($val as $value){
                $statement .= $value->toSql() . ', ';
            }
            $statement = substr($statement, 0, -2) . '), ';
        }

        $statement = substr($statement, 0, -2);

        return $statement;
    }
}