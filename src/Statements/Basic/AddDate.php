<?php
namespace Database\Statements\Basic;

class AddDate extends Date {
    protected $_column = null;
    protected $_value = null;
    protected $_alias = null;
    private $_interval = null;

    public function __construct(string $val, string $interval, string $alias = null, bool $isVal = false){
        parent::__construct($val, $alias, $isVal);
        $this->_interval = new Interval($interval);
    }

    public function toSql(): string {
        return 'ADDDATE('. ($this->_column !== null ? $this->_column->toSql() : $this->_value->toSql()) . ', ' . $this->_interval->toSql() . ')' . ($this->_alias !== null ? $this->_alias->toSql() : '');
    }
}