<?php
namespace Statements\Cases;
use Database\AbstractClasses\Statement_Abstract;
use Database\Statements\Basic\Alias;
use Database\Statements\Basic\Field;
use Database\Statements\Basic\Value;

/**
 * Class Cases
 * @package Statements\Cases
 */
class Cases extends Statement_Abstract {
    /**
     * @var Field|null
     */
    protected $_field = null;
    /**
     * @var array
     */
    protected $_when = [];
    /**
     * @var ElseStatement|null
     */
    protected $_else = null;

    protected $_alias = null;

    /**
     * Cases constructor.
     * @param array|null $_when
     * @param string|null $_field
     * @param string|null $_else
     * @param Alias|null $_alias
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    public function __construct(?object $_when = null, ?string $_field = null, ?string $_else = null, ?Alias $_alias = null) {
        if($_when !== null){
            $this->setParts($_when, $_field, $_else, $_alias);
        }
    }

    public function addWhen(object $_when, ?Value $_then = null) {
        $this->_when[] = new When($_when, $_then);
        return $this;
    }

    public function addField(string $_field){
        $this->_field = new Field($_field);
        return $this;
    }

    public function addElse($_else){
        $this->_else = new ElseStatement($_else);
        return $this;
    }

    public function addAlias($_alias){
        $this->_alias = $_alias;
        return $this;
    }

    /**
     * Cases constructor.
     * @param array $_when
     * @param string|null $_field
     * @param string|null $_else
     * @param Alias|null $_alias
     * @throws \Database\Exceptions\DatabaseExceptions
     */
    private function setParts(array $_when, ?string $_field, ?string $_else, ?Alias $_alias = null) {
        foreach($_when as $value){
            $this->_when[] = new When($value[0], $value[1] ?? null);
        }

        if($_field !== null){
            $this->_field = new Field($_field);
        }

        if($_else !== null){
            $this->_else = new ElseStatement($_else);
        }

        if($_alias !== null){
            $this->_alias = $_alias;
        }
    }

    /**
     * @return string
     */
    public function toSql(): string{

        $case = 'CASE ';

        if($this->_field !== null){
            $case .= $this->_field->toSql() . ' ';
        }

        foreach($this->_when as $key => $when){
            $case .= $when->toSql() . ' ';
        }

        if($this->_else !== null){
            $case .= $this->_else->toSql() . ' ';
        }

        $case .= 'END ';

        if($this->_alias !== null){
            $case .= $this->_alias->toSql() . ' ';
        }

        return $case;
    }
}