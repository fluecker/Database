<?php
namespace Database\AbstractClasses;

use Config\MainConnection;
use Database\Config\Config;
use Database\Exceptions\DatabaseConnectionExceptions;
use Database\Exceptions\DatabaseExceptions;
use Database\Exceptions\DatabaseQueryException;
use Database\Exceptions\NoConnectionExceptions;
use Database\Functions\DatabaseFunctions;
use Database\Functions\DatabaseLog;
use Database\Functions\ExecutionTime;

/**
 * Class Database_Abstract
 * @package Database\AbstractClasses
 */
abstract class Database_Abstract {
    /**
     * @var null
     */
    protected $_function = null;
    /**
     * @var null
     */
    protected $_where = null;
    /**
     * @var null
     */
    protected $_order = null;
    /**
     * @var null
     */
    protected $_group = null;
    /**
     * @var null
     */
    protected $_join = null;
    /**
     * @var null
     */
    protected $_fields = null;
    /**
     * @var null|\mysqli
     */
    protected $_connection = null;
    /**
     * @var null
     */
    protected $_result = null;
    /**
     * @var null
     */
    protected $_method = null;
    /**
     * @var string
     */
    protected $_last_query = '';
    /**
     * @var string
     */
    protected $_funcname = '';

    /**
     * @return null
     */
    public function getFunction()
    {
        return $this->_function;
    }

    /**
     * @return null
     */
    public function getWhere()
    {
        return $this->_where;
    }

    /**
     * @return null
     */
    public function getOrder()
    {
        return $this->_order;
    }

    /**
     * @return null
     */
    public function getGroup()
    {
        return $this->_group;
    }

    /**
     * @return null
     */
    public function getJoin()
    {
        return $this->_join;
    }

    /**
     * @return null
     */
    public function getFields()
    {
        return $this->_fields;
    }

    /**
     * @return null
     */
    public function getResult()
    {
        return $this->_result;
    }

    /**
     * @return null
     */
    public function getConnection() {
        return $this->_connection;
    }

    /**
     * @return bool
     */
    public function isConnected() {
        if($this->_connection->connect_errno) {
            return false;
        }

        return true;
    }

    /**
     * @param MainConnection $settings
     * @return bool
     * @throws DatabaseConnectionExceptions
     */
    protected function setConnection(MainConnection $settings) : bool {
        try {
            if($this->_connection === null) {
                $this->_connection = new \mysqli($settings->getHost(), $settings->getUsername(), $settings->getPassword(), $settings->getDatabase(), $settings->getPort());
            }

            if($this->_connection->connect_errno) {
                throw new NoConnectionExceptions('No Connection', Config::getInstance()->getLog());
            }

            if($settings->getCharset() !== ''){
                DatabaseFunctions::setCharset($settings->getCharset(), $this);
            }

            $this->_connection->query("SET lc_time_names = 'de_DE'");

            return true;
        } catch(\Exception $ex) {
            throw new DatabaseConnectionExceptions($this->_connection->error, Config::getInstance()->getLog());
        }
    }

    /**
     * @param string|null $query
     * @return array|bool|object|null
     * @throws DatabaseExceptions
     * @throws DatabaseQueryException
     * @throws NoConnectionExceptions
     */
    protected function execute(string $query = null){

        $executionTime = null;

        if($query === null) {
            $query = $this->createQuery();
        }

        if ($this->_connection || $this->_config->isDebug()) {

            if($this->_config->isTimer()){ //Set start time
                $executionTime = new ExecutionTime();
                $executionTime->start();
            }

            if(!$this->_config->isDebug()) {
                 $this->_result = $this->_connection->query($query); //execute query
            }

            if($this->_config->isTimer()){ //Set start time
                $executionTime->end();
            }

            $this->_function = [];

            //if database log is active, log all queries
            if($this->_config->getLog()->isEnabled()){
                DatabaseLog::add($query, $this->_config->getLog(), $this->_method, $executionTime);
            }
        } else {
            throw new NoConnectionExceptions('No Connection', Config::getInstance()->getLog());
        }

        if(!$this->_config->isDebug()) {
            if ($this->_result) {
                if($this->_funcname === 'Select') {
                    $this->_funcname = '';
                    return $this->getQueryResult($this->_result, $this->_connection);
                } else {
                    $this->_funcname = '';
                    return true;
                }
            } else {
                throw new DatabaseQueryException($this->_connection->error, Config::getInstance()->getLog());
            }
        } else {
            $this->_funcname = '';
            return null;
        }
    }

    /**
     * Erzeugt die Query und speichert sie.
     * @return string
     */
    private function createQuery(): string {
        foreach($this->_function as $function){
            $this->_last_query .= $function->toSql();
        }
        return $this->_last_query;
    }

    /**
     * Erzeugt das Datanbankresult
     * @param $result
     * @param $connection
     * @return array|object
     * @throws DatabaseExceptions
     * @throws NoConnectionExceptions
     */
    public function getQueryResult($result, $connection){
        if($connection) {
            if($result) {
                return $this->prepareResult($result);
            } else {
                throw new DatabaseExceptions('Es ist ein Fehler im Datanbank Result aufgetreten.', Config::getInstance()->getLog());
            }
        } else {
            throw new NoConnectionExceptions('No Connection', Config::getInstance()->getLog());
        }
    }

    /**
     * Bereitet das Result vor und gibt es als stdClass zurück
     * @param \mysqli_result $result
     * @return array | object
     */
    private function prepareResult(\mysqli_result $result) {
        $return = [];

        while($content = $result->fetch_object()) {
            $return[] = $content;
        }

        if(is_array($return) && count($return) === 1){
            $return = $return[0];
        }

        return $return;
    }

    /**
     * @throws NoConnectionExceptions
     */
    public function disconnect(){
        if($this->_connection) {
            $this->_connection->close();
        } else {
            throw new NoConnectionExceptions('No Connection', Config::getInstance()->getLog());
        }
    }
}