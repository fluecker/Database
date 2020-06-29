<?php
namespace Database\Statements\Where;


use Database\AbstractClasses\Statement_Abstract;
use Database\Exceptions\DatabaseStatementExceptions;
use Database\Functions\DatabaseFunctions;
use Database\Statements\Basic\Separator;
use Database\Statements\Where\Functions\ColumnStatement;
use Database\Statements\Where\Functions\CommonStatement;
use Database\Statements\Where\Functions\InStatement;
use Database\Statements\Where\Functions\OrStatement;
use Database\Statements\Where\Functions\SubSelectWhere;

/**
 * Class Where
 * @package Database\Statements\Where
 */
class Where extends Statement_Abstract
{
    /**
     * @var array
     */
    protected $_collection = [];
    /**
     * @var string
     */
    private $_separator = null;

    /**
     * Where constructor.
     * @param array|null $where
     * @throws DatabaseStatementExceptions
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function __construct(array $where = null){
        if($where !== null){
            $this->setWhere($where);
        }
        $this->_separator = new Separator('AND');
    }

    /**
     * @param array $where
     * @throws DatabaseStatementExceptions
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function setWhere(array $wheres): void {
        foreach($wheres as $where) {
            if (DatabaseFunctions::getArrayDepth($where) < 1) {
                if (is_array($where) && count($where) > 0) {
                    if ($where[0] !== '') {
                        $this->_collection[] = new CommonStatement($where[0], $where[1], isset($where[2]) ? $where[2] : null);
                    } else {
                        throw new DatabaseStatementExceptions('Column cannot be empty', null);
                    }
                } else {
                    throw new DatabaseStatementExceptions('Where cannot be empty', null);
                }
            } else {
                $this->_collection[] = new ColumnStatement($where);
            }
        }
    }

    /**
     * @param array|null $statement
     * @return $this|mixed
     */
    public function addOr(array $statement = null){
        if($statement !== null) {
            $this->_collection[] = new OrStatement($statement);
            return $this;
        } else {
            if(end($this->_collection) instanceof OrStatement){
                return end($this->_collection);
            } else {
                $this->_collection[] = new OrStatement();
                return end($this->_collection);
            }
        }
    }

    /**
     * @param string|null $field
     * @param bool $new
     * @return SubSelectWhere
     * @throws DatabaseStatementExceptions
     */
    public function addSubSelect(string $field = null, bool $new = false): SubSelectWhere {
        if(end($this->_collection) instanceof SubSelectWhere && !$new){
            return end($this->_collection);
        } else {
            $this->_collection[] = new SubSelectWhere($field);
            return end($this->_collection);
        }
    }

    /**
     * @param string|null $field
     * @param array|null $values
     * @param bool $new
     * @return InStatement
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function addInStatement(string $field = null, array $values = null, bool $new = false): InStatement {
        if(end($this->_collection) instanceof InStatement && !$new){
            return end($this->_collection);
        } else {
            $this->_collection[] = new InStatement($field, $values);
            return end($this->_collection);
        }
    }

    /**
     * @return string
     */
    public function toSql(): string {

        $statement = '';

        if(count($this->_collection) > 0) {
            $statement = "WHERE ";
        }

        foreach($this->_collection as $key => $w) {
            $statement .= $w->toSql() . $this->_separator->toSql();
        }

        //Remove ' AND '
        $statement = substr($statement, 0, -5);

        return $statement;
    }
}