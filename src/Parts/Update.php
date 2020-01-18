<?php
namespace Database\Parts;


use Database\AbstractClasses\Parts_Abtract;
use Database\Interfaces\Part;
use Database\Statements\Basic\Field;
use Database\Statements\UpdateFields;
use Database\Statements\Where\Where;

/**
 * Class Update
 * @package Database\Parts
 */
class Update extends Parts_Abtract implements Part {

    /**
     * @var array
     */
    protected $_sql_parts = [];

    /**
     * @var array
     */
    protected $_queryBuild = [
        'table',
        'update_fields',
        'where'
    ];

    /**
     * @param string $table
     * @return Update
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function addTable(string $table): Update {
        $this->_sql_parts['table'] = new Field($table);
        return $this;
    }

    /**
     * @param null $fields
     * @return $this Update
     */
    public function addFields($fields = null): self {
        if (!isset($this->_sql_parts['update_fields'])) {
            $this->_sql_parts['update_fields'] = new UpdateFields($fields);
        } else {
            $this->_sql_parts['update_fields']->setFields($fields);
        }
        return $this;
    }

    /**
     * @param array|null $where
     * @return $this|Where
     * @throws \Database\Exceptions\DatabaseExceptions
     * @throws \Database\Exceptions\DatabaseStatementExceptions
     */
    public function addWhere(array $where = null) {
        if($where !== null) {
            if(!isset($this->_sql_parts['where'])){
                $this->_sql_parts['where'] = new Where($where);
            } else {
                $this->_sql_parts['where']->setWhere($where);
            }
            return $this;
        } else {
            if(!isset($this->_sql_parts['where'])) {
                $this->_sql_parts['where'] = new Where();
            }
            return $this->_sql_parts['where'];
        }
    }

    /**
     * @param string|null $function
     * @return string
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function toSql(string $function = null): string {
        return parent::toSql('UPDATE');
    }
}