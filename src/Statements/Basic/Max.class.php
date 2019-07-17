<?php
namespace Database\Statements\Basic;


use Database\AbstractClasses\Basic_Abstract;
use Database\Functions\DatabaseFunctions;

class Max extends Basic_Abstract
{
    private $_field = '';
    protected $_alias = null;

    public function __construct(string $field, string $alias = null){
        parent::__construct($alias);
        $this->_field = new Field($field);
    }

    public function toSql(): string{
        return 'MAX(' . $this->_field->toSql() . ')' . ($this->_alias !== null ? $this->_alias->toSql() : '');
    }
}