<?php
namespace Database\Statements\Basic;

use Database\Functions\DatabaseFunctions;

class Separator
{
    private $_separator = '=';

    public function __construct(string $separator = null){
        if($separator !== null) {
            $this->_separator = DatabaseFunctions::real_escape_string($separator);
        }
    }

    public function toSql():string {
        return ' ' . $this->_separator . ' ';
    }
}