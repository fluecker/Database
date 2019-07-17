<?php
namespace Database\Statements;

use Database\AbstractClasses\Statement_Abstract;
use Database\Exceptions\DatabaseStatementExceptions;
use Database\Functions\DatabaseFunctions;

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

    /**
     * @return array
     */
    public function getUpdateFields(): array
    {
        return $this->_update_fields;
    }

    /**
     * @param array | string $fields
     * @throws DatabaseStatementExceptions
     */
    public function setUpdateFields($fields){
        if(is_array($fields) && count($fields) > 0){
            foreach($fields as $key => $value){
                if($this->validateField($key) && $this->validateField($value)){
                    $this->_update_fields[DatabaseFunctions::real_escape_string($key)] = DatabaseFunctions::real_escape_string($value);
                }
            }
        } else {
            throw new DatabaseStatementExceptions('Field cannot be empty');
        }
    }
}