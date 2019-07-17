<?php
namespace Database\AbstractClasses;


use Database\Exceptions\DatabaseStatementExceptions;
use Database\Statements\Where\Functions\BetweenStatement;
use Database\Statements\Where\Functions\ColumnStatement;
use Database\Statements\Where\Functions\IsNullStatement;

/**
 * Class Where
 * @package Database\AbstractClasses
 * @method Statement_Abstract addColumnComparison(array $columns)
 * @method Statement_Abstract setColumnComparison(array $columns)
 * @method Statement_Abstract addIsNull(array $columns)
 * @method Statement_Abstract setIsNull(array $columns)
 * @method Statement_Abstract addBetween(array $columns)
 * @method Statement_Abstract setBetween(array $columns)
 */

abstract class Statement_Abstract {
    protected $_mandatory = false;
    protected $_collection = [];

    /**
     * @return bool
     */
    public function isMandatory(): bool
    {
        return $this->_mandatory;
    }

    public function __call($name, $arguments){
        switch ($name){
            case 'setBetween':
            case 'addBetween':{
                $this->_collection[] = new BetweenStatement($arguments);
                break;
            }
            case 'addIsNull':
            case 'setIsNull':{
                if(count($arguments) > 1){
                    $this->_collection[] = new IsNullStatement($arguments);
                } else {
                    foreach ($arguments[0] as $argument) {
                        $this->_collection[] = new IsNullStatement($argument);
                    }
                }
                break;
            }
            case 'addColumnComparison':
            case 'setColumnComparison':{
                foreach($arguments[0] as $columns) {
                    $this->_collection[] = new ColumnStatement($columns);
                }
                break;
            }
        }
        return $this;
    }

    protected function toSql() : string{
        return '';
    }

    /**
     * Validiert den Inhalt von Feldbezeichnungen
     * @param string $field
     * @return bool
     * @throws DatabaseStatementExceptions
     */
    protected function validateField(string $field){
        if($field == '') {
            throw new DatabaseStatementExceptions('Field cannot be empty');
        } elseif(is_numeric($field)) {
            throw new DatabaseStatementExceptions('Field cannot be an numeric value');
        }

        return true;
    }
}