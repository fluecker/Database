<?php
namespace Database\Config;


use Database\Exceptions\DatabaseExceptions;

class Config {

    private static $_instance = null;

    private $_debug = false;
    private $_log = false;
    private $_num_rows = false;
    private $_timer = false;
    private $_log_destination = 'file'; //all, file, database
    private $_echo = false;
    private $_log_table_columns = [];
    private $_log_table_values = [];
    private $_log_table_name = '';
    private $_log_file_path = '/Log';
    private $_log_file_name = 'Query.log';
    private $_log_use_main_connection = true;

    private $_main_connection = [
        'host' => null,
        'user' => null,
        'pass' => null,
        'prefix' => null,
        'database' => null,
        'port' => null,
        'charset' => null,
        'timezone' => null,
    ];

    private $_log_connection = [
        'host' => null,
        'user' => null,
        'pass' => null,
        'prefix' => null,
        'database' => null,
        'port' => null,
        'charset' => null,
        'timezone' => null,
    ];

    private $_db_validate = [
        'host',
        'user',
        'pass',
        'database',
        'port',
    ];

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
     * @return string
     */
    public function getLogDestination(): string {
        return $this->_log_destination;
    }

    /**
     * @return bool
     */
    public function isEcho(): bool {
        return $this->_echo;
    }

    /**
     * @return array
     */
    public function getLogTableColumns(): array {
        return $this->_log_table_columns;
    }

    /**
     * @return array
     */
    public function getLogTableValues(): array {
        return $this->_log_table_values;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function setLogTableValues(string $key, $value): void {
        $this->_log_table_values[$key] = $value;
    }

    /**
     * @return string
     */
    public function getLogTableName(): string {
        return $this->_log_table_name;
    }

    /**
     * @return string
     */
    public function getLogFilePath(): string {
        return $this->_log_file_path;
    }

    /**
     * @return string
     */
    public function getLogFileName(): string {
        return $this->_log_file_name;
    }

    /**
     * @return bool
     */
    public function isLogUseMainConnection(): bool {
        return $this->_log_use_main_connection;
    }

    /**
     * @param string $key
     * @return string
     */
    public function getMainConnection(string $key): string {
        return $this->_main_connection[$key];
    }

    /**
     * @param string|array $key
     * @return array
     */
    public function getLogConnection(string $key = null) {
        if($key === null){
            return $this->_log_connection;
        } else {
            return $this->_log_connection[$key];
        }
    }


    /**
     * @param null $settings
     * @return Config
     */
    public static function getInstance($settings = null) : self{
        if (null === self::$_instance) {
            self::$_instance = new self($settings);
        }
        return self::$_instance;
    }

    private function __construct(array $settings) {
        $this->setSettings($settings);
    }

    /**
     * @param array $settings
     * @throws DatabaseExceptions
     */
    private function setSettings(array $settings){
        foreach($settings as $key => $setting){

            $var = '_' . $key;
            if(is_array($setting)){
                foreach($setting as $innerKey => $innerSetting){
                    $this->$var[$innerKey] = $innerSetting;
                }
            } else {
                $this->$var = $setting;
            }
        }

        $this->validateConfig();
    }

    private function validateConfig(){
        foreach($this->_db_validate as $val){
            if(!$this->_debug && ($this->_main_connection[$val] === null || $this->_main_connection[$val] === '')){
                throw new DatabaseExceptions('Field "' . $val . '" cannot be empty or null on main connection array');
            }

            if(($this->_log_destination == 'all' || $this->_log_destination == 'database') && !$this->_log_use_main_connection && ($this->_log_connection[$val] === null || $this->_log_connection[$val] === '')){
                throw new DatabaseExceptions('Field "' . $val . '" cannot be empty or null on log connection array');
            }
        }

        if(($this->_log_destination == 'all' || $this->_log_destination == 'database')){
            if($this->_log_table_name === '' || $this->_log_table_name === null) {
                throw new DatabaseExceptions('Table name cannot be empty');
            }

            if(count($this->_log_table_columns) == 0 || count($this->_log_table_values) == 0){
                throw new DatabaseExceptions('Log table column and value are required values');
            }

            if(count($this->_log_table_values) !== count($this->_log_table_columns)){
                throw new DatabaseExceptions('Log table columns and values must have the same count');
            }
        }


    }
}