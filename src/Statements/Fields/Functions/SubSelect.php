<?php
namespace Database\Statements\Fields\Functions;


use Database\Config\Config;
use Database\Exceptions\DatabaseStatementExceptions;
use Database\Parts\Select;
use Database\Statements\Basic\Alias;
use Database\Statements\Limit;

class SubSelect extends Select {
    private $_limit = null;
    private $_alias = null;

    public function __construct(string $alias = null){
        parent::__construct();
        $this->_limit = new Limit(1);

        if($alias !== null){
            $this->_alias = new Alias($alias);
        } else {
            throw new DatabaseStatementExceptions('Field cannot be empty', Config::getInstance()->getLog());
        }
    }

    public function toSql(string $function = null): string {
        return '(' . parent::toSql($function) . ' ' . $this->_limit->toSql() . ')' . ($this->_alias !== null ? $this->_alias->toSql() : '');
    }
}