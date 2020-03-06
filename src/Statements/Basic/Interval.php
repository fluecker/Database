<?php
namespace Database\Statements\Basic;


use Database\Config\Config;
use Database\Exceptions\DatabaseExceptions;

class Interval
{
    private $_value = '';

    public function __construct(string $interval){
        if($interval === ''){
            throw new DatabaseExceptions('Interval cannot be empty', Config::getInstance()->getLog());
        } else {
            $this->_value = $interval;
        }
    }

    public function toSql(): string {
        return 'INTERVAL ' . $this->_value;
    }
}