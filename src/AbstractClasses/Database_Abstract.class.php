<?php
/**
 * Created by PhpStorm.
 * User: Fabian
 * Date: 15.03.2019
 * Time: 17:27
 */

namespace Database\AbstractClasses;

use Database\Exceptions\DatabaseExceptions;
use Database\Exceptions\DatabaseQueryException;
use Database\Exceptions\NoConnectionExceptions;
use Database\Functions\DatabaseFunctions;
use Database\Functions\DatabaseLog;
use Database\Functions\ExecutionTime;

abstract class Database_Abstract {
    protected $_function = null;
    protected $_where = null;
    protected $_order = null;
    protected $_group = null;
    protected $_join = null;
    protected $_fields = null;
    protected $_connection = null;
    protected $_result = null;
    protected $_isLog = [];
    protected $_isDebug = null;
    protected $_isTimer = null;
    protected $_method = null;
    protected $_last_query = '';

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
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * @param array $settings
     * @return bool
     * @throws DatabaseExceptions
     */
    protected function setConnection(array $settings) : bool {
        try {
            $this->_connection = new \mysqli($settings['host'], $settings['user'], $settings['pass'], $settings['database'], $settings['port']);

            if(!$this->_connection) {
                throw new NoConnectionExceptions('No Connection');
            }

            if(isset($settings['charset']) && $settings['charset'] !== ''){
                DatabaseFunctions::setCharset($settings['charset'], $this);
            }

            $this->_connection->query("SET lc_time_names = 'de_DE'");

            return true;
        } catch(\Exception $ex) {
            throw new DatabaseExceptions($ex->getMessage());
        }
    }

    /**
     * @param string|null $query
     * @return array|null
     * @throws DatabaseExceptions
     * @throws DatabaseQueryException
     * @throws NoConnectionExceptions
     */
    protected function execute(string $query = null){

        $executionTime = null;

        if($query === null) {
            $query = $this->createQuery();
        }

        if ($this->_connection || $this->_isDebug) {

            if($this->_isTimer){ //Set start time
                $executionTime = new ExecutionTime();
                $executionTime->start();
            }

            if(!$this->_isDebug) {
                $this->_result = $this->_connection->query($query); //execute query
            }

            if($this->_isTimer){ //Set start time
                $executionTime->end();
            }

            //if database log is active, log all querys
            if(isset($this->_isLog['enabled']) && $this->_isLog['enabled']){
                DatabaseLog::add($query, $this->_isLog, $this->_method, $executionTime);
            }
        } else {
            throw new NoConnectionExceptions('No Connection');
        }

        if(!$this->_isDebug) {
            if ($this->_result) {
                return $this->getQueryResult($this->_result, $this->_connection);
            } else {
                throw new DatabaseQueryException($this->_connection->error);
            }
        } else {
            return null;
        }
    }

    /**
     * Erzeugt die Query und speichert sie.
     * @return string
     */
    private function createQuery(): string {
        $this->_last_query = $this->_function->toSql();
        return $this->_last_query;
    }

    /**
     * Erzeugt das Datanbankresult
     * @param $result
     * @param $connection
     * @return array|null
     * @throws DatabaseExceptions
     * @throws NoConnectionExceptions
     */
    public function getQueryResult($result, $connection){
        if($connection) {
            if($result) {
                return $this->prepareResult($result);
            } else {
                throw new DatabaseExceptions('Es ist ein Fehler im Datanbank Result aufgetreten.');
            }
        } else {
            throw new NoConnectionExceptions('No Connection');
        }
    }

    /**
     * Bereitet das Result vor und gibt es als stdClass zurück
     * @param \mysqli_result $result
     * @return array
     */
    private function prepareResult(\mysqli_result $result): array{
        $return = [];

        while($content = $result->fetch_object()) {
            $return[] = $content;
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
            throw new NoConnectionExceptions('No Connection');
        }
    }
}