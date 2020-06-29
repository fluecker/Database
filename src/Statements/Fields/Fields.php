<?php
namespace Database\Statements\Fields;


use Database\AbstractClasses\Statement_Abstract;
use Database\Config\Config;
use Database\Exceptions\DatabaseExceptions;
use Database\Exceptions\DatabaseStatementExceptions;
use Database\Statements\Basic\AddDate;
use Database\Statements\Basic\Date;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Max;
use Database\Statements\Basic\Min;
use Database\Statements\Fields\Functions\SubSelect;

/**
 * Class Fields
 * @package Statments
 */
class Fields extends Statement_Abstract {
    /**
     * @var bool
     */
    protected $_mandatory = true;

    /**
     * @var array
     */
    protected $_fields = [];

    /**
     * Fields constructor.
     * @param null $fields
     * @throws DatabaseExceptions
     * @throws DatabaseStatementExceptions
     */
    public function __construct($fields = null){
        if($fields !== null){
            $this->setFields($fields);
        }
    }

    /**
     * @return string
     */
    public function getFields():string {
        return $this->toSql();
    }

    /**
     * @param null $fields
     * @return $this
     * @throws DatabaseExceptions
     * @throws DatabaseStatementExceptions
     */
    public function setFields($fields = null){
        if($fields !== null) {
            if (is_array($fields) && count($fields) > 0) {
                foreach ($fields as $field) {
                    if(is_array($field) && isset($field[1])){
                        $hash = md5($field[0]);
                        if (!isset($this->_fields[$hash]) && $this->validateField($field[0])) {
                            $this->_fields[$hash] = new Field($field[0], $field[1]);
                        }
                    } else {
                        $hash = md5($field);
                        if (!isset($this->_fields[$hash]) && $this->validateField($field)) {
                            $this->_fields[$hash] = new Field($field);
                        }
                    }
                }
            } elseif (!is_array($fields) && $fields !== '') {
                $hash = md5((string)$fields);
                if ($this->validateField((string)$fields)) {
                    $this->_fields[$hash] = new Field((string)$fields);
                }
            } else {
                throw new DatabaseStatementExceptions('Field cannot be empty', Config::getInstance()->getLog());
            }
        }

        return $this;
    }

    /**
     * @param $name
     * @param $arguments
     * @return Fields
     * @throws DatabaseExceptions
     */
    public function __call($name, $arguments): self{
        switch ($name){
            case 'setMax':{
                $this->_fields[] = new Max($arguments[0], isset($arguments[1]) ? $arguments[1] : null);
                break;
            }
            case 'setMin':{
                $this->_fields[] = new Min($arguments[0], isset($arguments[1]) ? $arguments[1] : null);
                break;
            }
            case 'setDate':{
                $this->_fields[] = new Date($arguments[0], $arguments[1], isset($arguments[2]) ? $arguments[2] : null);
                break;
            }
            case 'addDateInterval':{
                $this->_fields[] = new AddDate($arguments[0], $arguments[1], isset($arguments[2]) ? $arguments[2] : null, isset($arguments[3]) ? $arguments[3] : false);
                break;
            }
            default:{
                throw new DatabaseExceptions('Call undefined function: ' . $name, Config::getInstance()->getLog());
                break;
            }
        }

        return $this;
    }

    /**
     * @param string|null $alias
     * @param bool $new
     * @return SubSelect
     * @throws DatabaseStatementExceptions
     */
    public function addSubSelect(string $alias = null, bool $new = false): SubSelect {
        if(end($this->_fields) instanceof SubSelect && !$new){
            return end($this->_fields);
        } else {
            $this->_fields[] = new SubSelect($alias);
            return end($this->_fields);
        }
    }

    /**
     * @return string
     */
    public function toSql(): string{

        $fields = '';

        foreach($this->_fields as $key => $field){
            $fields .= $field->toSql() . ', ';
        }

        $fields = substr($fields, 0, -2);

        return $fields;
    }
}