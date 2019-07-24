<?php
/**
 * Created by PhpStorm.
 * User: Fabian
 * Date: 15.03.2019
 * Time: 17:27
 */

namespace Database\AbstractClasses;

use Database\Config\Config;
use Database\Exceptions\DatabaseExceptions;
use Database\Exceptions\DatabaseQueryException;
use Database\Exceptions\NoConnectionExceptions;
use Database\Functions\DatabaseFunctions;
use Database\Functions\DatabaseLog;
use Database\Functions\ExecutionTime;

abstract class Database_Abstract {
    protected $_function = null;
    protected $_connection = null;
    protected $_result = null;
    protected $_method = null;
    protected $_last_query = '';
    protected $_funcname = '';
    protected $_num_rows = 0;

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
     * @return bool
     * @throws DatabaseExceptions
     */
    protected function setConnection() : bool {
        try {
            $this->_connection = new \mysqli(
                Config::getInstance()->getMainConnection('host'),
                Config::getInstance()->getMainConnection('user'),
                Config::getInstance()->getMainConnection('pass'),
                Config::getInstance()->getMainConnection('database'),
                Config::getInstance()->getMainConnection('port')
            );

            if(!$this->_connection) {
                throw new NoConnectionExceptions('No Connection');
            }

            $this->_connection->query("SET lc_time_names = 'de_DE'");

            return true;
        } catch(\Exception $ex) {
            throw new DatabaseExceptions($ex->getMessage());
        }
    }

    /**
     * @param string|null $query
     * @return array|null|bool
     * @throws DatabaseExceptions
     * @throws DatabaseQueryException
     * @throws NoConnectionExceptions
     */
    protected function execute(string $query = null){

        $executionTime = null;

        if($query === null) {
            $query = $this->createQuery();
        }

        if ($this->_connection || Config::getInstance()->isDebug()) {

            if(Config::getInstance()->isTimer()){ //Set start time
                $executionTime = new ExecutionTime();
                $executionTime->start();
            }

            if(!Config::getInstance()->isDebug()) {
                $this->_result = $this->_connection->query($query); //execute query
                if(Config::getInstance()->isNumRows()) {
                    $this->_num_rows = DatabaseFunctions::numRows($this);
                }
            }

            if(Config::getInstance()->isTimer()){ //Set start time
                $executionTime->end();
            }

            $this->_function = [];

            //if database log is active, log all querys
            if(Config::getInstance()->isLog()){
                DatabaseLog::add($query, $this->_num_rows, $this->_method, $executionTime);
            }
        } else {
            throw new NoConnectionExceptions('No Connection');
        }

        if(!Config::getInstance()->isDebug()) {
            if ($this->_result) {
                if($this->_funcname === 'Select') {
                    return $this->getQueryResult($this->_result, $this->_connection);
                } else {
                    return true;
                }
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
        foreach($this->_function as $function){
            $this->_last_query .= $function->toSql();
        }
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
     * @return array Query Result
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