<?php
namespace Statements\Cases;


use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Value;

class ElseStatement extends Statement_Abstract {
    protected $_value = null;

    public function __construct($_value) {
        $this->_value = new Value($_value);
    }

    public function toSql(): string {
        return 'ELSE ' . $this->_value->toSql();
    }
}