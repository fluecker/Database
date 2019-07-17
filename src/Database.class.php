<?php
namespace Database;
use Database\AbstractClasses\Database_Abstract;
use Database\Exceptions\NoConnectionExceptions;
use Database\Functions\DatabaseFunctions;
use Database\Parts\Select;

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

    public function select(string $method = ''): Select{
        if($this->_method === null){
            $this->_method = $method;
        }

        if($this->_function === null){
            $this->_funcname = 'Select';
            $this->_function = new Select();
        }
        return $this->_function;
    }

    public function update($table, $fields, $where = null)
    {
        $query = '';

        //Query Start
        $query = "UPDATE " . self::clear_string($table) . " SET ";

        //Add Columns
        foreach($fields as $field)
        {
            if(!isset($field['ocolumn']))
                $query .= self::clear_string($field['column']) . ' = \'' . trim(self::clear_string($field['value'])) . '\', ';
            else
                $query .= self::clear_string($field['column']) . ' = ' . self::clear_string($field['ocolumn']) . ', ';
        }

        //Remove ', '
        $query = substr($query, 0, -2) . ' ';

        //Add where
        if($where !== null && isset($where[0]['column'])) {
            $query .= $this->addWhere($where);
        }

        //Remove Whitespaces
        $query = trim($query);

        //Send the Query
        return self::sendQuery($query);
    }

    public function insert($table, $fields)
    {
        $query = '';

        //Query Start
        $query = "INSERT INTO " . self::clear_string($table) . " ";

        //Add Columnset
        $query .= "( ";
        foreach($fields as $field)
        {
            $query .= self::clear_string($field['column']) . ', ';
        }

        //Remove ', '
        $query = substr($query, 0, -2) . ') VALUES ( ';

        //Add Values
        foreach($fields as $field)
        {
            $query .= '\'' . trim(self::clear_string($field['value'])) . '\', ';
        }

        //Remove ', '
        $query = substr($query, 0, -2) . ')';

        //Remove Whitespaces
        $query = trim($query);

        //Send the Query
        return self::sendQuery($query);
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