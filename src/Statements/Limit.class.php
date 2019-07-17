<?php
namespace Database\Statements;


use Database\AbstractClasses\Statement_Abstract;

class Limit extends Statement_Abstract {
    private $_limit = 0;
    private $_offset = 0;

    public function __construct(int $limit, int $offset = 0){
        $this->_limit = $limit;
        $this->_offset = $offset;
    }

    public function toSql(): string{
        return 'LIMIT ' . $this->_limit . ($this->_offset > 0 ? ' OFFSET ' . $this->_offset : '');
    }
}