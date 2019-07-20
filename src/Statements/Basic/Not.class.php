<?php


namespace Database\Statements\Basic;


use Database\AbstractClasses\Basic_Abstract;

class Not extends Basic_Abstract {
    public function toSql(): string {
        return 'NOT';
    }
}