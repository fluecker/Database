<?php
namespace Database\Statements\Where\Functions;


use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Field;
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

    /**
     * Like constructor.
     * @param string $field
     * @param string $value
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function __construct(string $field, string $value) {
        $this->_column = new Field($field);
        $this->_separator = new Separator('LIKE');
        $this->_value = new Value($value);
    }

    /**
     * @return string
     */
    public function toSql(): string {
        return $this->_column->toSql() . $this->_separator->toSql() . $this->_value->toSql();
    }
}