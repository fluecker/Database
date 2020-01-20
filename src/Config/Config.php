<?php
namespace Database\Config;

use AbstractClasses\ObjectAbstract;
use Config\Log;
use Config\LogConnection;
use Config\MainConnection;
use Database\Exceptions\DatabaseConfigExceptions;

/**
 * Class Config
 * @package Database\Config
 */
class Config extends ObjectAbstract {

    /**
     * @var null
     */
    private static $_instance = null;

    /**
     * @var bool
     */
    private $_debug = false;
    /**
     * @var bool
     */
    private $_log = null;
    /**
     * @var bool
     */
    private $_num_rows = false;
    /**
     * @var bool
     */
    private $_timer = false;

    /**
     * @var null
     */
    private $_main_connection = null;

    /**
     * @var array
     */
    private $_db_validate = [
        'host',
        'username',
        'password',
        'database',
        'port',
    ];

    /**
     * @param bool $debug
     */
    public function setDebug(bool $debug): void{
        $this->_debug = $debug;
    }

    /**
     * @param bool $timer
     */
    public function setTimer(bool $timer): void{
        $this->_timer = $timer;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool {
        return $this->_debug;
    }

    /**
     * @return bool
     */
    public function isLog(): bool {
        return $this->_log;
    }

    /**
     * @return bool
     */
    public function isNumRows(): bool {
        return $this->_num_rows;
    }

    /**
     * @return bool
     */
    public function isTimer(): bool {
        return $this->_timer;
    }

    /**
     * @param null $main_connection
     */
    public function setMainConnection($main_connection): void{
        $this->_main_connection = $main_connection;
    }

    /**
     * @return null|MainConnection
     */
    public function getMainConnection(): ?MainConnection{
        if($this->_main_connection === null){
            $this->_main_connection = new MainConnection();
        }

        return $this->_main_connection;
    }

    /**
     * @return null|Log
     */
    public function getLog(): ?Log{
        if($this->_log === null){
            $this->_log = new Log();
        }

        return $this->_log;
    }

    /**
     * @return Config
     */
    public static function getInstance() : self{
        if (null === self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {}

    /**
     * @throws DatabaseConfigExceptions
     */
    public function validateConfig(){
        foreach($this->_db_validate as $val){
            $function = 'get' . ucfirst($val);
            if(!$this->isDebug() && ($this->getMainConnection()->$function() === null || $this->getMainConnection()->$function() === '')){
                throw new DatabaseConfigExceptions('Field "' . $val . '" cannot be empty or null on main connection array', $this);
            }

            if(($this->getLog()->getLogDestination() == 'all' || $this->getLog()->getLogDestination() == 'database') && !$this->getLog()->isLogUseMainConnection() && ($this->getLog()->getLogConnection()->$function() === null || $this->getLog()->getLogConnection()->$function() === '')){
                throw new DatabaseConfigExceptions('Field "' . $val . '" cannot be empty or null on log connection array', $this);
            }
        }

        if(($this->getLog()->getLogDestination() == 'all' || $this->getLog()->getLogDestination() == 'database')){
            if($this->getLog()->getLogConnection() === null){
                throw new DatabaseConfigExceptions('No log connection found', $this);
            } else {
                if($this->getLog()->getLogConnection()->getLogTableName() === '') {
                    throw new DatabaseConfigExceptions('Log table name cannot be empty', $this);
                }

                if(count($this->getLog()->getLogConnection()->getLogTableColumns()) == 0 || count($this->getLog()->getLogConnection()->getLogTableValues()) == 0){
                    throw new DatabaseConfigExceptions('Log table column and value are required values', $this);
                }

                if(count($this->getLog()->getLogConnection()->getLogTableValues()) !== count($this->getLog()->getLogConnection()->getLogTableColumns())){
                    throw new DatabaseConfigExceptions('Log table columns and values must have the same count', $this);
                }
            }

        }
    }

    /**
     * @param null $host or Main Connection or mysqli object
     * @param null $username or Log Connection
     * @param null $password or common Settings
     * @param null $database
     * @param int $port
     * @param string $charset
     * @param null $socket
     * @return Config
     * @throws DatabaseConfigExceptions
     */
    public static function readConfig($host = null, $username = null, $password = null, $database = null, $port = 3306, $charset = 'utf8', $socket = null){

        $config = new Config();

        // if host an array, all main connection params passed over this
        if (is_array($host)) {
            if(!isset($host['config']) && !isset($host['connection_data'])) {
                foreach ($host as $key => $val) {
                    $function = 'set';
                    if (is_numeric($key)) {
                        $function .= ucfirst(self::mapNumericToAsso($key));
                    } else {
                        $function .= ucfirst($key);
                    }
                    $config->getMainConnection()->$function($val);
                }
            } else {
                if (isset($host['config'])) {
                    if (isset($host['config']['debug'])) {
                        $config->setDebug($host['config']['debug']);
                    }

                    if (isset($host['config']['timer'])) {
                        $config->setTimer($host['config']['timer']);
                    }

                    if (isset($host['config']['log']['enabled'])) {
                        $config->getLog()->setEnabled($host['config']['log']['enabled']);
                    }

                    if (isset($host['config']['log']['destination'])) {
                        $config->getLog()->setLogDestination($host['config']['log']['destination']);
                    }

                    if (isset($host['config']['log']['echo'])) {
                        $config->getLog()->setEcho($host['config']['log']['echo']);
                    }

                    if (isset($host['config']['log']['file']['log_path'])) {
                        $config->getLog()->getFile()->setLogPath($host['config']['log']['file']['log_path']);
                    }

                    if (isset($host['config']['log']['file']['log_file'])) {
                        $config->getLog()->getFile()->setLogFile($host['config']['log']['file']['log_file']);
                    }

                    if (isset($host['config']['log']['database']['use_main_connection'])) {
                        $config->getLog()->setLogUseMainConnection($host['config']['log']['database']['use_main_connection']);
                    }

                    if (isset($host['config']['log']['database']['connection_data'])) {
                        $config->getLog()->setLogConnection(new LogConnection($host['config']['log']['database']['connection_data']));
                    }

                    if (isset($host['config']['log']['database']['table_data'])) {
                        if (isset($host['config']['log']['database']['table_data']['name'])) {
                            $config->getLog()->getLogConnection()->setLogTableName($host['config']['log']['database']['table_data']['name']);
                        }

                        if (isset($host['config']['log']['database']['table_data']['columns'])) {
                            $config->getLog()->getLogConnection()->setLogTableColumns($host['config']['log']['database']['table_data']['columns']);
                        }

                        if (isset($host['config']['log']['database']['table_data']['values'])) {
                            $config->getLog()->getLogConnection()->setLogTableValues($host['config']['log']['database']['table_data']['values']);
                        }
                    }
                }
                if(isset($host['connection_data'])) {
                    $config->setMainConnection(new MainConnection($host['connection_data']));
                }
            }
        }

        // if username an array, all log connection params passed over this
        if (is_array($username)) {
            foreach ($username as $key => $val) {
                $function = 'set';
                if(is_numeric($key)){
                    $function .= ucfirst(self::mapNumericToAsso($key));
                } else {
                    $function .= ucfirst($key);
                }
                $config->getLog()->getLogConnection()->$function($val);
            }
        }

        // if come the connection data normal
        if (!is_object($host) && !is_array($host) && $host !== null && !is_array($username) && $username !== null) {
            foreach (func_get_args() as $key => $val) {
                if ($key < 7) {
                    $function = 'set';
                    if (is_numeric($key)) {
                        $function .= ucfirst(self::mapNumericToAsso($key));
                    } else {
                        $function .= ucfirst($key);
                    }
                    $config->getMainConnection()->$function($val);
                }
            }
        }

        if($host === null && $username === null && $password === null && $database === null){
            $config->setDebug(true);
            $config->getLog()->setEnabled(true);
            $config->getLog()->setEcho(true);
        }

        $config->validateConfig();

        return $config;
    }

    private static function mapNumericToAsso($key): string {
        switch($key){
            case 0:{
                return 'host';
                break;
            }
            case 1:{
                return 'username';
                break;
            }
            case 2:{
                return 'password';
                break;
            }
            case 3:{
                return 'database';
                break;
            }
            case 4:{
                return 'port';
                break;
            }
            case 5:{
                return 'charset';
                break;
            }
            case 6:{
                return 'prefix';
                break;
            }
            case 7:{
                return 'timezone';
                break;
            }
            default:{
                throw new DatabaseConfigExceptions('Unknown key in config', Config::getInstance());
            }
        }
    }
}