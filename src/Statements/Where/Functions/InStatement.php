<?php
namespace Database\Statements\Where\Functions;


use Database\AbstractClasses\Statement_Abstract;
use Database\Config\Config;
use Database\Exceptions\DatabaseExceptions;
use Database\Parts\Select;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Separator;
use Database\Statements\Basic\Value;

/**
 * Class InStatement
 * @package Database\Statements\Where\Functions
 */
class InStatement extends Statement_Abstract {
    /**
     * @var Field|null
     */
    private $_column = null;
    /**
     * @var Separator|null
     */
    private $_separator = null;
    /**
     * @var array
     */
    private $_values = [];
    /**
     * @var null
     */
    private $_select = null;

    /**
     * InStatement constructor.
     * @param string $field
     * @param array|null $values
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function __construct(string $field, array $values = null) {
        $this->_column = new Field($field);
        $this->_separator = new Separator('IN');

        if($values !== null){
            foreach($values as $value) {
                $this->_values[] = new Value($value);
            }
        }

    }

    /**
     * @return Select
     */
    public function addSubSelect(): Select {
        if(count($this->_values) === 0) {
            if ($this->_select instanceof Select) {
                return $this->_select;
            } else {
                $this->_select = new Select();
                return $this->_select;
            }
        } else {
            throw new DatabaseExceptions('Values already been set', Config::getInstance()->getLog());
        }
    }

    public function toSql(): string{
        return $this->_column->toSql() . $this->_separator->toSql() . ($this->_select !== null ? '(' . $this->_select->toSql() . ')' : '(' . implode(', ', $this->_values) . ')');
    }
}