<?php
namespace Database\Statements\Where\Functions;

use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Not;
use Database\Statements\Basic\Separator;
use Database\Statements\Basic\Value;

/**
 * Class BetweenStatement
 * @package Database\Statements\Where\Functions
 */
class BetweenStatement extends Statement_Abstract {
    /**
     * @var Separator|null
     */
    private $_separator = null;
    /**
     * @var Value|null
     */
    private $_value1 = null;
    /**
     * @var Value|null
     */
    private $_value2 = null;
    /**
     * @var Field|null
     */
    protected $_column = null;

    protected $_not = null;

    /**
     * BetweenStatement constructor.
     * @param string $field
     * @param $value1
     * @param $value2
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function __construct(string $field, $value1, $value2, $not = false){
        $this->_column = new Field($field);
        $this->_value1 = new Value($value1);
        $this->_value2 = new Value($value2);

        $this->_separator = new Separator('AND');

        if($not){
            $this->_not = new Not();
        }
    }

    /**
     * @return string
     */
    public function toSql():string {
        return $this->_column->toSql() . ($this->_not !== null ? $this->_not->toSql() . ' ' : '') . 'BETWEEN ' . $this->_value1->toSql() . $this->_separator->toSql() . $this->_value2->toSql();
    }
}