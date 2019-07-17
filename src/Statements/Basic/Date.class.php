<?php
namespace Database\Statements\Basic;


use Database\AbstractClasses\Basic_Abstract;

class Date extends Basic_Abstract {
    protected $_column = null;
    protected $_value = null;
    protected $_alias = null;

    public function __construct(string $val, string $alias = null, bool $isVal = false){
        parent::__construct($alias);
        if($isVal){
            $this->_value = new Value($val);
        } else {
            $this->_column = new Field($val);
        }
    }

    public function toSql(): string {
        return 'DATE(' . ($this->_column !== null ? $this->_column->toSql() : $this->_value->toSql()) . ')' . ($this->_alias !== null ? $this->_alias->toSql() : '');
    }
}