<?php
namespace Database\Statements\Where\Functions;


use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Not;
use Database\Statements\Basic\Separator;
use Database\Statements\Basic\Value;

/**
 * Class Like
 * @package Database\Statements\Where\Functions
 */
class LikeStatement extends Statement_Abstract {
    /**
     * @var Field|null
     */
    private $_column = null;
    /**
     * @var Separator|null
     */
    private $_separator = null;
    /**
     * @var Value|null
     */
    private $_value = null;

    private $_not = null;

    /**
     * Like constructor.
     * @param string $field
     * @param string $value
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function __construct(string $field, string $value, bool $not = null) {
        $this->_column = new Field($field);
        $this->_separator = new Separator('LIKE');
        $this->_value = new Value($value);

        if($not){
            $this->_not = new Not();
        }
    }

    /**
     * @return string
     */
    public function toSql(): string {
        return $this->_column->toSql() . ($this->_not !== null ? $this->_not->toSql() . ' ' : '') . $this->_separator->toSql() . $this->_value->toSql();
    }
}