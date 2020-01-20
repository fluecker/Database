<?php
namespace Config;

use AbstractClasses\ObjectAbstract;

/**
 * Class File
 * @package Database
 */
class File extends ObjectAbstract {
    /**
     * @var string
     */
    protected $_log_path = '';
    /**
     * @var string
     */
    protected $_log_file = 'query.log';

    /**
     * @return string
     */
    public function getLogPath(): string{
        return $this->_log_path;
    }

    /**
     * @param string $log_path
     */
    public function setLogPath(string $log_path): void{
        $this->_log_path = $log_path;
    }

    /**
     * @return string
     */
    public function getLogFile(): string{
        return $this->_log_file;
    }

    /**
     * @param string $log_file
     */
    public function setLogFile(string $log_file): void{
        $this->_log_file = $log_file;
    }

    /**
     * File constructor.
     */
    public function __construct(){
        $this->_log_path = dirname(__DIR__) . '/log';
    }
}