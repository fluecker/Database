<?php
namespace Database\Statements\Basic;


use Database\Config\Config;
use Database\Exceptions\DatabaseExceptions;
use Database\Functions\DatabaseFunctions;

class Alias
{
    private $_name = '';
    private $_separator = null;

    public function __construct(string $name) {
        if(empty($name) || $name === ''){
            throw new DatabaseExceptions('Alias cannot be empty', Config::getInstance()->getLog());
        } else {
            $this->_name = DatabaseFunctions::real_escape_string($name);
        }

        $this->_separator = new Separator('AS');
    }

    public function toSql(){
        return $this->_separator->toSql() . '`' . $this->_name . '`';
    }
}