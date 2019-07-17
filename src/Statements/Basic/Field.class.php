<?php
namespace Database\Statements\Basic;

use Database\AbstractClasses\Basic_Abstract;
use Database\Exceptions\DatabaseExceptions;
use Database\Functions\DatabaseFunctions;

class Field extends Basic_Abstract {
    private $_name = '';
    protected $_alias = null;

    public function __construct(string $name, string $alias = null){
        parent::__construct($alias);
        if(empty($name) || $name === ''){
            throw new DatabaseExceptions('Column cannot be empty');
        } else {
            $this->_name = DatabaseFunctions::real_escape_string($name);
        }
    }

    public function toSql(): string{
        return '`' . $this->_name . '`' . ($this->_alias !== null ? $this->_alias->toSql() : '');
    }
}