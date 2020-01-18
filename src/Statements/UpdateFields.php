<?php
namespace Database\Statements;

use Database\AbstractClasses\Statement_Abstract;
use Database\Exceptions\DatabaseStatementExceptions;
use Database\Functions\DatabaseFunctions;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Value;
use Database\Statements\Where\Functions\CommonStatement;

/**
 * Class Fields
 * @package Statments
 */
class UpdateFields extends Statement_Abstract {
    protected $_mandatory = true;

    /**
     * @var array
     */
    protected $_update_fields = [];

    public function __construct(array $fields = null){
        if($fields !== null){
            $this->setUpdateFields($fields);
        }
    }

    /**
     * @param array | string $fields
     * @throws DatabaseStatementExceptions
     */
    public function setUpdateFields(array $fields){
        if(is_array($fields) && count($fields) > 0){
            foreach($fields as $field){
                $tmpFields = new CommonStatement($field[0], $field[1]);
                $this->_update_fields[] = $tmpFields;
            }
        } else {
            throw new DatabaseStatementExceptions('Field cannot be empty');
        }
    }

    public function toSql(): string {

        $statement = 'SET ';

        foreach($this->_update_fields as $field){
            $statement .= $field->toSql() . ', ';
        }

        $statement = substr($statement, 0, -2);

        return $statement;
    }
}