<?php
namespace Database\Statements;


use Database\AbstractClasses\Statement_Abstract;

class Union extends Statement_Abstract {
    public function toSql():string {
        return ' UNION ';
    }
}