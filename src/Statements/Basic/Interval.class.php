<?php
namespace Database\Statements\Basic;


use Database\Exceptions\DatabaseExceptions;

class Interval
{
    private $_value = '';

    public function __construct(string $interval){
        if($interval === ''){
            throw new DatabaseExceptions('Interval cannot be empty');
        } else {
            $this->_value = $interval;
        }
    }

    public function toSql(): string {
        return 'INTERVAL ' . $this->_value;
    }
}