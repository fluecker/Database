<?php
namespace Statements\Cases;

use Database\AbstractClasses\Statement_Abstract;
use Database\Config\Config;
use Database\Exceptions\DatabaseExceptions;
use Database\Statements\Basic\Value;
use Database\Statements\Where\Functions\ColumnStatement;
use Database\Statements\Where\Functions\CommonStatement;
use Database\Statements\Where\Functions\InStatement;
use Database\Statements\Where\Functions\IsNullStatement;
use Database\Statements\Where\Functions\LikeStatement;
use Database\Statements\Where\Functions\OrStatement;

/**
 * Class When
 * @package Statements\Cases
 */
class When extends Statement_Abstract {
    /**
     * @var ColumnStatement|CommonStatement|InStatement|IsNullStatement|LikeStatement|OrStatement|object|null
     */
    protected $_field = null;
    /**
     * @var Value|null
     */
    protected $_then = null;

    /**
     * When constructor.
     * @param object $_field
     * @param Value|null $_then
     * @throws DatabaseExceptions
     */
    public function __construct(object $_field, ?Value $_then) {
        if(
            $_field instanceof CommonStatement ||
            $_field instanceof ColumnStatement ||
            $_field instanceof InStatement ||
            $_field instanceof IsNullStatement ||
            $_field instanceof LikeStatement ||
            $_field instanceof OrStatement
        ){
            $this->_field = $_field;
        } else {
            throw new DatabaseExceptions('Case field must be a part of Statements', Config::getInstance()->getLog());
        }

        if($_then !== null){
            $this->_then = $_then;
        }
    }

    public function toSql(): string {
        return 'WHEN ' . $this->_field->toSql() . ' ' . ($this->_then !== null ? 'THEN ' . $this->_then->toSql() : '');
    }
}