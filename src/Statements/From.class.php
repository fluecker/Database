<?php
namespace Database\Statements;


use Database\AbstractClasses\Statement_Abstract;
use Database\Exceptions\DatabaseStatementExceptions;
use Database\Functions\DatabaseFunctions;
use Database\Statements\Basic\Field;

class From extends Statement_Abstract {

    protected $_mandatory = true;
    /**
     * @var array
     */
    protected $_tables = [];

    /**
     * @return string
     */
    public function getTables(): string
    {
        return $this->toSql();
    }

    /**
     * Setzt den FROM Parameter
     * @param $tables string | array
     * @throws DatabaseStatementExceptions
     */
    public function setTables($tables){
        if(is_array($tables) && count($tables) > 0){ //if is tables an array
            foreach($tables as $key => $table){
                if(is_array($table) && count($table) > 0){// Tables with aliases
                    $this->setTablesInner($table[0], $table[1]);
                } else {//multiple tables without aliases
                    $this->_tables[] = new Field($table);
                }
            }
        } elseif(!is_array($tables) && $tables !== ''){ //if is tables an string
            if($this->validateField($tables)) {
                $this->_tables[] = new Field($tables);
            }
        } else {
            throw new DatabaseStatementExceptions('Table cannot be empty');
        }
    }

    private function setTablesInner(string $table, string $alias){
        if (!is_numeric($table)) {
            if ($this->validateField($table) && $this->validateField($alias)) {
                $this->_tables[] = new Field($table, $alias);
            }
        } else { //only tables
            if ($this->validateField($table)) {
                $this->_tables[] = new Field($table);
            }
        }
    }

    /**
     * Erzeugt den FROM Part der Query und gibt diesen zurück
     * @return string
     */
    public function toSql() : string {

        $from = '';

        if(count($this->_tables) > 0) {
            $from = 'FROM ';
        }

        foreach($this->_tables as $key => $table){
            $from .= $table->toSql() . ', ';
        }

        //Remove coma and whitespace
        $from = substr($from, 0, -2);

        return $from;
    }
}