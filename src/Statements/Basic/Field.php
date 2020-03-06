<?php
namespace Database\Statements\Basic;

use Database\AbstractClasses\Basic_Abstract;
use Database\Config\Config;
use Database\Exceptions\DatabaseExceptions;
use Database\Functions\DatabaseFunctions;

class Field extends Basic_Abstract {
    private $_name = '';
    protected $_alias = null;

    /**
     * @return bool
     */
    public function isMandatory(): bool {
        return true;
    }

    public function __construct(string $name, string $alias = null){
        parent::__construct($alias);
        if(empty($name) || $name === ''){
            throw new DatabaseExceptions('Column cannot be empty', Config::getInstance()->getLog());
        } else {

            if(DatabaseFunctions::allowedMysqlFunction($name)){
                $this->_name = $name;
            } else {
                foreach(explode('.', $name) as $parts){
                    $this->_name .= '`' . DatabaseFunctions::real_escape_string($parts). '`.';
                }

                $this->_name = substr($this->_name , 0, -1);
            }
        }
    }

    public function toSql(): string{
        return $this->_name . ($this->_alias !== null ? $this->_alias->toSql() : '');
    }
}