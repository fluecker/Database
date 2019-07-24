<?php
namespace Database;
use Database\AbstractClasses\Database_Abstract;
use Database\Config\Config;
use Database\Exceptions\NoConnectionExceptions;
use Database\Functions\DatabaseFunctions;
use Database\Parts\Delete;
use Database\Parts\Insert;
use Database\Parts\Select;
use Database\Parts\Update;
use Database\Statements\Union;

/**
 * Class Database
 * @author Fabian Lücker
 * @copyright 2014
 * @version 2.0
 * @package fablueck/database
 * @package Database
 */
class Database extends Database_Abstract {
    /**
     * @var string
     */
    protected $db_link = '';
    /**
     * @var array
     */
    protected $_function = [];
    /**
     * @var bool
     */
    protected $_isLog = false;
    /**
     * @var string
     */
    protected $_logPath = '';
    /**
     * @var bool
     */
    protected $_isTimer = false;
    /**
     * @var bool
     */
    protected $_isDebug = false;
    /**
     * @var null
     */
    protected $_method = null;
    /**
     * @var string
     */
    protected $_funcname = '';
    /**
     * @var int
     */
    protected static $_function_index = -1;
    /**
     * @var bool
     */
    private $_new_query = true;

    /**
     * @return string
     */
    public function getFuncname(): string
    {
        return $this->_funcname;
    }

    /**
     * @var null
     */
    protected static $_instance = null;

    /**
     * @param null $settings
     * @param bool $new Create a new instance
     * @return Database
     * @throws Exceptions\DatabaseExceptions
     * @throws NoConnectionExceptions
     */
    public static function getInstance($settings = null, $new = false) : Database{
        if(!$new) {
            if (null === self::$_instance || $settings !== null) {
                self::$_instance = new self($settings);
            }
            return self::$_instance;
        } else {
            return new self($settings);
        }
    }

    /**
     * Database constructor.
     * @param array $settings
     * @throws Exceptions\DatabaseExceptions
     * @throws NoConnectionExceptions
     */
    private function __construct(array $settings){

        Config::getInstance($settings);
        if(Config::getInstance()->isDebug() === false) {
            if ($this->setConnection()) {
                DatabaseFunctions::setCharset($this, Config::getInstance()->getMainConnection('charset'));
            } else {
                throw new NoConnectionExceptions('No Connection');
            }
        }
    }

    /**
     * @return \mysqli_result
     */
    public function getResult() :\mysqli_result{
        return parent::getResult();
    }

    /**
     * @return \mysqli
     */
    public function getConnection() : \mysqli{
        return parent::getConnection();
    }

    /**
     * @param string|null $query
     * @return array|bool|null
     * @throws Exceptions\DatabaseExceptions
     * @throws Exceptions\DatabaseQueryException
     * @throws NoConnectionExceptions
     */
    public function execute(string $query = null){
        return parent::execute($query);
    }

    /**
     *
     */
    public function disableLog(){
        if(isset($this->_isLog['enabled'])){
            $this->_isLog['enabled'] = false;
        }
    }

    /**
     *
     */
    public function enableLog(){
        if(isset($this->_isLog['enabled'])){
            $this->_isLog['enabled'] = true;
        }
    }

    /**
     *
     */
    public function addUnion(){
        $this->_function[self::$_function_index]->addUnion();
        $this->_new_query = true;
    }

    /**
     * @param string $method
     * @return Select
     */
    public function select(string $method = ''): Select {
        if($this->_method === null){
            $this->_method = $method;
        }

        if($this->_funcname !== 'Select' || $this->_new_query){
            $this->_funcname = 'Select';
            $this->_function[++self::$_function_index] = new Select();
            $this->_new_query = false;
        }
        return $this->_function[self::$_function_index];
    }

    /**
     * @param string $method
     * @return Update
     */
    public function update(string $method = ''): Update {
        if($this->_method === null){
            $this->_method = $method;
        }

        if($this->_funcname !== 'Update'){
            $this->_funcname = 'Update';
            $this->_function[++self::$_function_index] = new Update();
        }
        return $this->_function[self::$_function_index];
    }

    /**
     * @param string $method
     * @return Insert
     */
    public function insert(string $method = ''): Insert {
        if($this->_method === null){
            $this->_method = $method;
        }

        if($this->_funcname !== 'Insert'){
            $this->_funcname = 'Insert';
            $this->_function[++self::$_function_index] = new Insert();
        }
        return $this->_function[self::$_function_index];
    }

    /**
     * @param string $method
     * @return Delete
     */
    public function delete(string $method = ''): Delete {
        if($this->_method === null){
            $this->_method = $method;
        }

        if($this->_funcname !== 'Delete'){
            $this->_funcname = 'Delete';
            $this->_function[++self::$_function_index] = new Delete();
        }
        return $this->_function[self::$_function_index];
    }
}