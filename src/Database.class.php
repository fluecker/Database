<?php
namespace Database;
use Database\AbstractClasses\Database_Abstract;
use Database\Exceptions\NoConnectionExceptions;
use Database\Functions\DatabaseFunctions;
use Database\Parts\Insert;
use Database\Parts\Select;
use Database\Parts\Update;

/**
 * Class Database
 * @author Fabian Lücker
 * @copyright 2014
 * @version 2.0
 * @package fablueck/database
 * @package Database
 */
class Database extends Database_Abstract {
    protected $db_link = '';
    protected $_function = null;
    protected $_isLog = false;
    protected $_logPath = '';
    protected $_isTimer = false;
    protected $_isDebug = false;
    protected $_method = null;
    private $_funcname = '';

    /**
     * @return string
     */
    public function getFuncname(): string
    {
        return $this->_funcname;
    }

    protected static $_instance = null;

    /**
     * @param null $settings
     * @return Database
     * @throws NoConnectionExceptions
     * @throws \Exceptions\DatabaseExceptions
     */
    public static function getInstance($settings = null) : Database{
        if (null === self::$_instance || $settings !== null) {
            self::$_instance = new self($settings);
        }
        return self::$_instance;
    }

    /**
     * Database constructor.
     * @param array $settings
     * @throws NoConnectionExceptions
     * @throws \Exceptions\DatabaseExceptions
     */
    private function __construct(array $settings){
        if(isset($settings['config'])) {
            $this->setConfig($settings['config']);
        }

        if($this->_isDebug === false) {
            if ($this->setConnection($settings['connection_data'])) {
                DatabaseFunctions::selectDatabase($settings['connection_data']['database'], $this);
                DatabaseFunctions::setCharset($settings['connection_data']['charset'], $this);
            } else {
                throw new NoConnectionExceptions('No Connection');
            }
        }
    }

    private function setConfig(array $config){
        $this->_isDebug = isset($config['debug']) ? $config['debug'] : false;
        $this->_isTimer = isset($config['timer']) ? $config['timer'] : false;
        $this->_isLog = $config['log'];
    }

    public function getResult() :\mysqli_result{
        return parent::getResult();
    }

    public function getConnection() : \mysqli{
        return parent::getConnection();
    }

    public function execute(string $query = null){
        return parent::execute($query);
    }

    public function select(string $method = ''): Select {
        if($this->_method === null){
            $this->_method = $method;
        }

        if($this->_function === null){
            $this->_funcname = 'Select';
            $this->_function = new Select();
        }
        return $this->_function;
    }

    public function update(string $method = ''): Update {
        if($this->_method === null){
            $this->_method = $method;
        }

        if($this->_function === null){
            $this->_funcname = 'Update';
            $this->_function = new Update();
        }
        return $this->_function;
    }

    public function insert(string $method = ''): Insert {
        if($this->_method === null){
            $this->_method = $method;
        }

        if($this->_function === null){
            $this->_funcname = 'Insert';
            $this->_function = new Insert();
        }
        return $this->_function;
    }

    public function delete($table, $where)
    {
        $query = '';

        //Query Start
        $query = 'DELETE FROM '. self::clear_string($table) . ' ';

        //Add where
        if($where !== null && isset($where[0]['column'])) {
            $query .= $this->addWhere($where);
        }

        //Remove ' AND '
        $query = substr($query, 0, -5). ' ';

        //Remove Whitespaces
        $query = trim($query);

        //Send the Query
        return self::sendQuery($query);
    }
}