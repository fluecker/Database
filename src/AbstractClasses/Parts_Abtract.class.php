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
                if(is_array($this->_sql_parts[$queryPart])){
                    foreach($this->_sql_parts[$queryPart] as $innerPart){
                        $part = $innerPart->toSql();
                        if ($innerPart->isMandatory() && $part === '') {
                            throw new DatabaseExceptions(get_class($innerPart) . ' must filled');
                        }
                        $query .= $innerPart->toSql() . ' ';
                    }
                } else {
                    $part = $this->_sql_parts[$queryPart]->toSql();
                    if ($this->_sql_parts[$queryPart]->isMandatory() && $part === '') {
                        throw new DatabaseExceptions(get_class($this->_sql_parts[$queryPart]) . ' must filled');
                    }
                    $query .= $this->_sql_parts[$queryPart]->toSql() . ' ';
                }

            }
        }

        return trim($query);
    }
}