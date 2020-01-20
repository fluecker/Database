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
 * @package     fablueck/php-database
 * @category    Database Access and secure query-builder
 * @author      Fabian Lücker <fabian@f-luecker.de>
 * @copyright   2014 - 2020
 * @version     1.2.3
 * @link        https://github.com/fluecker/Database
 * @license     MIT
 */
class Database extends Database_Abstract {
    protected $db_link = '';
    protected $_function = [];
    protected $_method = null;
    protected $_funcname = '';
    protected static $_function_index = -1;
    private $_new_query = true;
    protected $_last_query = '';
    protected $_config = null;

    /**
     * @return string
     */
    public function getFuncname(): string
    {
        return $this->_funcname;
    }

    protected static $_instance = null;

    public static function getInstance($host = null, $username = null, $password = null, $database = null, $port = '', $charset = '', $socket = null) : Database {

        $config = Config::readConfig($host, $username, $password, $database, $port, $charset, $socket);

        if (null === self::$_instance) {
            self::$_instance = new self($config);
        }
        return self::$_instance;
    }

    public function setConfig($key, $value){
        $function = 'set' . ucfirst($key);
        $this->_config->$function($value);
    }

    public function __construct($host = null, $username = null, $password = null, $database = null, $port = '', $charset = '', $socket = null){

        if($host instanceof Config) {
            $this->_config = $host;
        } else {

            if($host instanceof \mysqli) {
                $this->_connection = $host;
                $host = '';
            }

            $this->_config = Config::readConfig($host, $username, $password, $database, $port, $charset, $socket);
        }

        if($this->_config->isDebug() === false) {
            if ($this->setConnection($this->_config->getMainConnection())) {
                DatabaseFunctions::selectDatabase($this->_config->getMainConnection()->getDatabase(), $this);
                DatabaseFunctions::setCharset($this->_config->getMainConnection()->getCharset(), $this);
            } else {
                throw new NoConnectionExceptions('No Connection', $this->_config);
            }
        }
    }

    public function getResult() :\mysqli_result{
        return parent::getResult();
    }

    public function getConnection() : \mysqli{
        return parent::getConnection();
    }

    public function execute(string $query = null){
        $this->_last_query = '';
        return parent::execute($query);
    }

    public function disableLog(){
        if(isset($this->_isLog['enabled'])){
            $this->_isLog['enabled'] = false;
        }
    }

    public function enableLog(){
        if(isset($this->_isLog['enabled'])){
            $this->_isLog['enabled'] = true;
        }
    }

    public function addUnion(){
        $this->_function[self::$_function_index]->addUnion();
        $this->_new_query = true;
    }

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

    public function getLastInsertId(){
        return DatabaseFunctions::getLastInsertID($this);
    }
}