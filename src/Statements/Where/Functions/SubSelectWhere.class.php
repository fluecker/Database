<?php
namespace Database\Statements\Where\Functions;


use Database\Exceptions\DatabaseStatementExceptions;
use Database\Parts\Select;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Separator;

class SubSelectWhere extends Select {

    private $_field = null;
    private $_separator = null;

    public function __construct(string $field = null){
        parent::__construct();

        if($field !== null){
            $this->_field = new Field($field);
        } else {
            throw new DatabaseStatementExceptions('Field cannot be empty');
        }
        $this->_separator = new Separator('=');
    }

    public function toSql(string $function = null): string {
        return $this->_field->toSql() . $this->_separator->toSql() . '(' . parent::toSql($function) . ')';
    }
}