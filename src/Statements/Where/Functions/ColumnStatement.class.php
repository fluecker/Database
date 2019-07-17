<?php
namespace Database\Statements\Where\Functions;

use Database\Statements\Basic\Field;
use Database\Statements\Basic\Separator;

/**
 * Class ColumnStatement
 * @package Statements\Where\Functions
 */
class ColumnStatement
{
    /**
     * @var string
     */
    private $_separator = null;
    /**
     * @var array
     */
    private $_fields = [];

    /**
     * ColumnStatement constructor.
     * @param array $column
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function __construct(array $column){
        $this->_fields[] = new Field($column[0]);
        $this->_fields[] = new Field($column[1]);
        $this->_separator = new Separator((isset($column[2]) ? $column[2] : null));
    }

    /**
     * @return string
     */
    public function toSql(): string{
        return $this->_fields[0]->toSql() . $this->_separator->toSql() . $this->_fields[1]->toSql();
    }
}