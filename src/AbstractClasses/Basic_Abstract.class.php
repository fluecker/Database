<?php
namespace Database\AbstractClasses;


use Database\Statements\Basic\Alias;

abstract class Basic_Abstract
{
    protected $_alias = null;

    public function __construct(string $alias = null) {
        if($alias !== null){
            $this->_alias = new Alias($alias);
        }
    }
}