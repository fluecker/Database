<?php
namespace Database\Statements;


use Database\AbstractClasses\Statement_Abstract;
use Database\Config\Config;
use Database\Exceptions\DatabaseExceptions;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Value;

class Having extends Statement_Abstract
{
    private $_column = null;
    private $_option = '';
    private $_value = null;

    public function __construct(string $column, string $option, $value) {
        $this->_column = new Field($column);
        $this->_option = $option;

        if($value !== '') {
            $this->_value = new Value($value);
        } else {
            throw new DatabaseExceptions('Value cannot be empty', Config::getInstance()->getLog());
        }
    }

    public function toSql():string {
        return 'HAVING ' . $this->_column->toSql() . ' ' . $this->_option . ' ' . $this->_value->toSql();
    }
}