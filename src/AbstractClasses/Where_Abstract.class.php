<?php
namespace Database\AbstractClasses;


use Database\Exceptions\DatabaseExceptions;
use Database\Statements\Basic\AddDate;
use Database\Statements\Basic\Date;

abstract class Where_Abstract {

    protected $_column = null;

    public function __call($name, $arguments){
        switch ($name){
            case 'date':{
                $this->_column[] = new Date($arguments[0], null, $arguments[1]);
                break;
            }
            case 'adddate':{
                $this->_column[] = new AddDate($arguments[0], $arguments[1], isset($arguments[2]) ? $arguments[2] : null, isset($arguments[3]) ? $arguments[3] : false);
                break;
            }
            default:{
                throw new DatabaseExceptions('Call undefined function: ' . $name);
            }
        }
        return $this;
    }
}