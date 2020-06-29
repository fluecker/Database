<?php
namespace Database\Statements\Where\Functions;

use Database\AbstractClasses\Where_Abstract;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Separator;

/**
 * Class ColumnStatement
 * @package Statements\Where\Functions
 */
class ColumnStatement extends Where_Abstract
{
    /**
     * @var string
     */
    private $_separator = null;
    /**
     * @var array
     */
    protected $_column = [];

    /**
     * ColumnStatement constructor.
     * @param array $input
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function __construct(array $input){
        if(is_array($input[0]) && isset($input[0][1])){
            $func = $input[0][1];
            $val = $input[0][0];
            $this->$func($val, isset($input[0][2]) ? $input[0][2] : false);
        } else {
            if(is_array($input[0]) && isset($input[0][0])){
                $this->_column[] = new Field($input[0][0]);
            } else {
                $this->_column[] = new Field($input[0]);
            }
        }

        if(is_array($input[1]) && isset($input[1][1])){
            $func = $input[1][1];
            $val = $input[1][0];
            $this->$func($val, isset($input[1][2]) ? $input[1][2] : false);
        } else {
            if(is_array($input[1]) && isset($input[1][0])){
                $this->_column[] = new Field($input[1][0]);
            } else {
                $this->_column[] = new Field($input[1]);
            }
        }

        $this->_separator = new Separator((isset($input[2][0]) ? $input[2][0] : null));
    }

    /**
     * @return string
     */
    public function toSql(): string{
        return $this->_column[0]->toSql() . $this->_separator->toSql() . $this->_column[1]->toSql();
    }
}