<?php
namespace Database\Parts;

use Database\AbstractClasses\Parts_Abtract;
use Database\Statements\Fields\Fields;
use Database\Statements\From;
use Database\Statements\Group;
use Database\Statements\Join;
use Database\Statements\Limit;
use Database\Statements\Order;
use Database\Statements\Where\Where;
use Database\Statements\LeftJoin;

/**
 * Class Select
 * @package Parts
 */
class Select extends Parts_Abtract {

    protected $_sql_parts = [];

    protected $_queryBuild = [
        'fields',
        'from',
        'join',
        'left_join',
        'where',
        'group',
        'order',
        'limit'
    ];

    public function __construct(){
        $this->_sql_parts['from'] = new From();
    }

    /**
     * @param null $fields
     * @return $this|mixed
     * @throws \Database\Exceptions\DatabaseExceptions
     * @throws \Database\Exceptions\DatabaseStatementExceptions
     */
    public function addFields($fields = null) {
        if($fields !== null) {
            if (!isset($this->_sql_parts['fields'])) {
                $this->_sql_parts['fields'] = new Fields($fields);
            } else {
                $this->_sql_parts['fields']->setFields($fields);
            }
            return $this;
        } else {
            if(!isset($this->_sql_parts['fields'])){
                $this->_sql_parts['fields'] = new Fields();
            }
            return $this->_sql_parts['fields'];
        }
    }

    /**
     * @param array|null $where
     * @return Select | Where
     */
    public function addWhere(array $where = null) {
        if($where !== null) {
            if(!isset($this->_sql_parts['where'])){
                $this->_sql_parts['where'] = new Where($where);
            } else {
                $this->_sql_parts['where']->setWhere($where);
            }
            return $this;
        } else {
            if(!isset($this->_sql_parts['where'])) {
                $this->_sql_parts['where'] = new Where();
            }
            return $this->_sql_parts['where'];
        }
    }

    /**
     * @param $from
     * @return Select
     */
    public function addFrom($from) : self{
        $this->_sql_parts['from']->setTables($from);
        return $this;
    }

    /**
     * @param int $limit
     * @param int $offset
     * @return Select
     */
    public function setLimit(int $limit, int $offset = 0): self {
        $this->_sql_parts['limit'] = new Limit($limit, $offset);
        return $this;
    }

    public function setGroup(array $columns = null): Group{
        $this->_sql_parts['group'] = new Group($columns);
        return $this->_sql_parts['group'];
    }

    public function setOrder(array $columns = null): Order{
        $this->_sql_parts['order'] = new Order($columns);
        return $this->_sql_parts['order'];
    }

    public function addLeftJoin(array $field, array $columns): Select{
        $this->_sql_parts['left_join'] = new LeftJoin($field, $columns);
        return $this;
    }

    public function addJoin(array $field, array $where): Select{
        $this->_sql_parts['join'] = new Join($field, $where);
        return $this;
    }

    public function toSql(string $function = null): string{
        return parent::toSql('SELECT');
    }
}