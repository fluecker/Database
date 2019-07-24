<?php


namespace Database\Statements;

use Database\AbstractClasses\Statement_Abstract;

class Distinct extends Statement_Abstract {
    private $_value = 'DISTINCT';

    public function toSql(): string {
        return ' ' . $this->_value . ' ';
    }
}