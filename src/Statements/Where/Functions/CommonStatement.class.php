<?php
namespace Database\Statements\Where\Functions;

use Database\AbstractClasses\Where_Abstract;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Separator;
use Database\Statements\Basic\Value;

/**
 * Class CommonStatement
 * @package Statements\Where\Functions
 */
class CommonStatement extends Where_Abstract
{
    /**
     * @var Field|string
     */
    protected $_column = null;
    /**
     * @var Value|string
     */
    private $_value = '';
    /**
     * @var string
     */
    private $_separator = null;

    /**
     * CommonStatement constructor.
     * @param string $column
     * @param string $value
     * @param string|null $separator
     * @param string|null $function
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function __construct(string $column, string $value, string $separator = null, string $function = null) {

        if($function !== null){
            $this->$function($column);
        } else {
            $this->_column = new Field($column);
        }

        $this->_value = new Value($value);
        $this->_separator = new Separator($separator);
    }

    /**
     * @return string
     */
    public function toSql():string {
        return $this->_column->toSql() . $this->_separator->toSql() . $this->_value->toSql();
    }
}