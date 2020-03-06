<?php
namespace Statements\Cases;

use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Value;

class Then extends Statement_Abstract {
    protected $_value = null;

    public function __construct(string $_value) {
        $this->_value = new Value($_value);
    }

    public function toSql(): string {
        return 'THEN ' . $this->_value->toSql();
    }
}