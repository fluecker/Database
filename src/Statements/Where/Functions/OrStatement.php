<?php
namespace Database\Statements\Where\Functions;


use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Separator;

/**
 * Class OrStatement
 * @package Statements\Where\Functions
 */
class OrStatement extends Statement_Abstract {
    /**
     * @var array
     */
    protected $_collection = [];
    /**
     * @var string
     */
    private $_separator = null;

    /**
     * OrStatement constructor.
     * @param array|null $statement
     */
    public function __construct(array $statement = null) {
        if($statement !== null) {
            $this->setOrStatement($statement);
        }
        $this->_separator = new Separator('OR');
    }

    /**
     * @param array $statement
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function setOrStatement(array $statement){
        foreach ($statement as $stateInner) {
            $this->_collection[] = new CommonStatement($stateInner[0], $stateInner[1], isset($stateInner[3]) ? $stateInner[3] : null);
        }
    }

    /**
     * @return OrStatement
     */
    public function OrStatement(): self {
        return $this;
    }

    /**
     * @return string
     */
    public function toSql(): string {
        $statement = '';

        $statement .= "(";
        foreach($this->_collection as $key => $or) {
            $statement .= $or->toSql() . $this->_separator->toSql();
        }

        $statement = substr($statement, 0, -4). ')';

        return $statement;
    }
}