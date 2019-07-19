<?php
namespace Database\Statements;


use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Value;

class FieldCollection extends Statement_Abstract {
    private $_fields = [];

    public function __construct(array $fields = null) {
        if($fields !== null){
            $this->setFields($fields);
        }
    }

    public function setFields(array $fields){
        foreach($fields as $field){
                $this->_fields[] = new Field($field);
        }
    }

    public function toSql(): string {

        $statement = '(';

        foreach($this->_fields as $field){
            $statement .= $field->toSql() . ', ';
        }

        $statement = substr($statement, 0, -2) . ')';

        return $statement;
    }
}