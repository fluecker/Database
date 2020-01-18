<?php
namespace Database\AbstractClasses;


use Database\Exceptions\DatabaseStatementExceptions;
use Database\Statements\Basic\Not;
use Database\Statements\Where\Functions\BetweenStatement;
use Database\Statements\Where\Functions\ColumnStatement;
use Database\Statements\Where\Functions\DateStatement;
use Database\Statements\Where\Functions\IsNullStatement;
use Database\Statements\Where\Functions\LikeStatement;

/**
 * Class Where
 * @package Database\AbstractClasses
 * @method Statement_Abstract addColumnComparison(array $columns)
 * @method Statement_Abstract setColumnComparison(array $columns)
 * @method Statement_Abstract addIsNull(array | string$columns)
 * @method Statement_Abstract addIsNotNull(array | string $columns)
 * @method Statement_Abstract setIsNull(array | string $columns)
 * @method Statement_Abstract addLike(string $field, string $value)
 * @method Statement_Abstract addNotLike(string $field, string $value)
 * @method Statement_Abstract setLike(string $field, string $value)
 * @method Statement_Abstract addBetween($field, $value1 = null, $value2 = null)
 * @method Statement_Abstract addNotBetween($field, $value1 = null, $value2 = null)
 * @method Statement_Abstract setBetween($field, $value1 = null, $value2 = null)
 * @method Statement_Abstract addDateComparison(string $field, string $datefield, string $interval, string $separator = null)
 * @method Statement_Abstract setDateComparison(string $field, string $datefield, string $interval, string $separator = null)
 */

abstract class Statement_Abstract {
    protected $_mandatory = false;
    protected $_collection = [];

    /**
     * @return bool
     */
    public function isMandatory(): bool {
        return $this->_mandatory;
    }

    public function __call($name, $arguments){

        $not = false;

        if(strstr(strtolower($name), 'not') !== false){
            $name = str_replace('not', '', strtolower($name));
            $not = true;
        }

        switch (strtolower($name)){
            case 'setbetween':
            case 'addbetween':{
                if(is_array($arguments[0])) {
                    foreach ($arguments[0] as $columns) {
                        $this->_collection[] = new BetweenStatement($columns[0], $columns[1], $columns[2], $not);
                    }
                } else {
                    $this->_collection[] = new BetweenStatement($arguments[0], $arguments[1], $arguments[2], $not);
                }
                break;
            }
            case 'addisnull':
            case 'setisnull':{
                if(!is_array($arguments[0]) || count($arguments[0]) === 1){
                    $this->_collection[] = new IsNullStatement($arguments[0], $not);
                } else {
                    foreach ($arguments[0] as $argument) {
                        $this->_collection[] = new IsNullStatement($argument, $not);
                    }
                }
                break;
            }
            case 'addcolumncomparison':
            case 'setcolumncomparison':{
                foreach($arguments[0] as $columns) {
                    $this->_collection[] = new ColumnStatement($columns);
                }
                break;
            }
            case 'adddatecomparison':
            case 'setdatecomparison':{
                $this->_collection[] = new DateStatement($arguments[0], $arguments[1], $arguments[2], isset($arguments[3]) ? $arguments[3] : null);
                break;
            }
            case 'addlike':
            case 'setlike':{
                if(is_array($arguments[0])) {
                    foreach ($arguments[0] as $columns) {
                        $this->_collection[] = new LikeStatement($columns[0], $columns[1], $not);
                    }
                } else {
                    $this->_collection[] = new LikeStatement($arguments[0], $arguments[1], $not);
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
            throw new DatabaseStatementExceptions('Field cannot be empty', []);
        } elseif(is_numeric($field)) {
            throw new DatabaseStatementExceptions('Field cannot be an numeric value', []);
        }

        return true;
    }
}