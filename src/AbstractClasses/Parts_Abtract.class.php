<?php
namespace Database\AbstractClasses;


use Database\Exceptions\DatabaseExceptions;

abstract class Parts_Abtract
{
    protected $_sql_parts = [];
    protected $_queryBuild = [];

    protected function toSql(string $function): string{
        $query = $function . ' ';

        foreach($this->_queryBuild as $queryPart){
            if(isset($this->_sql_parts[$queryPart])) {
                $part = $this->_sql_parts[$queryPart]->toSql();
                if ($this->_sql_parts[$queryPart]->isMandatory() && $part === '') {
                    throw new DatabaseExceptions(get_class($this->_sql_parts[$queryPart]) . ' must filled');
                }
                $query .= $this->_sql_parts[$queryPart]->toSql() . ' ';
            }
        }



        return trim($query);
    }
}