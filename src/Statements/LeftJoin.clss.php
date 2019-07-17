<?php
namespace Database\Statements;

class LeftJoin extends Join
{
    public function __construct(array $field, array $columns){
        parent::__construct($field, $columns);
    }

    public function toSql(): string {
        return 'Left ' . parent::toSql();
    }
}