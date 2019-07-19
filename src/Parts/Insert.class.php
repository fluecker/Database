<?php
namespace Database\Parts;


use Database\AbstractClasses\Parts_Abtract;
use Database\Interfaces\Part;
use Database\Statements\Basic\Field;
use Database\Statements\FieldCollection;
use Database\Statements\Fields\Fields;
use Database\Statements\UpdateFields;
use Database\Statements\ValueCollection;
use Database\Statements\Where\Where;

class Insert extends Parts_Abtract {

    /**
     * @var array
     */
    protected $_sql_parts = [];

    /**
     * @var array
     */
    protected $_queryBuild = [
        'table',
        'fields',
        'values'
    ];

    /**
     * @param null $fields
     * @return $this|mixed
     */
    public function addFields($fields): self {
        if (!isset($this->_sql_parts['fields'])) {
            $this->_sql_parts['fields'] = new FieldCollection($fields);
        } else {
            $this->_sql_parts['fields']->setFields($fields);
        }
        return $this;
    }

    /**
     * @param array $values
     * @return $this|mixed
     */
    public function addValues(array $values = null): self {
        if (!isset($this->_sql_parts['values'])) {
            $this->_sql_parts['values'] = new ValueCollection($values);
        } else {
            $this->_sql_parts['values']->setValues($values);
        }
        return $this;
    }

    /**
     * @param string $table
     * @return Update
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function addTable(string $table): self {
        $this->_sql_parts['table'] = new Field($table);
        return $this;
    }

    public function toSql(string $function = null): string {
        return parent::toSql('INSERT INTO');
    }
}