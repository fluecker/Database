<?php
namespace Database\AbstractClasses;


use Database\Config\Config;
use Database\Exceptions\DatabaseExceptions;
use Database\Statements\Union;

abstract class Parts_Abtract
{
    protected $_sql_parts = [];
    protected $_queryBuild = [];

    public function addUnion(){
        $this->_queryBuild[] = 'union';
        $this->_sql_parts['union'] = new Union();
    }

    protected function toSql(string $function): string{
        $query = $function . ' ';

        foreach($this->_queryBuild as $queryPart){
            if(isset($this->_sql_parts[$queryPart])) {
                if(is_array($this->_sql_parts[$queryPart])){
                    foreach($this->_sql_parts[$queryPart] as $innerPart){
                        $part = $innerPart->toSql();
                        if ($innerPart->isMandatory() && $part === '') {
                            throw new DatabaseExceptions(get_class($innerPart) . ' must filled', Config::getInstance()->getLog());
                        }
                        $query .= $innerPart->toSql() . ' ';
                    }
                } else {
                    $part = $this->_sql_parts[$queryPart]->toSql();
                    if ($this->_sql_parts[$queryPart]->isMandatory() && $part === '') {
                        throw new DatabaseExceptions(get_class($this->_sql_parts[$queryPart]) . ' must filled', Config::getInstance()->getLog());
                    }
                    $query .= $this->_sql_parts[$queryPart]->toSql() . ' ';
                }

            }
        }

        return $query;
    }
}