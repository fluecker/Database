<?php
namespace Config;

use AbstractClasses\ObjectAbstract;

class Log extends ObjectAbstract {
    /**
     * @var bool
     */
    private $_enabled = false;
    /**
     * @var bool
     */
    private $_echo = false;

    private $_file = null;

    /**
     * @var bool
     */
    private $_log_use_main_connection = true;
    /**
     * @var null
     */
    private $_log_connection = null;
    /**
     * @var string
     */
    private $_log_destination = 'file';  //all, file, database

    /**
     * @return bool
     */
    public function isEnabled(): bool
    {
        return $this->_enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        $this->_enabled = $enabled;
    }

    /**
     * @param bool $echo
     */
    public function setEcho(bool $echo): void
    {
        $this->_echo = $echo;
    }

    /**
     * @param bool $log_use_main_connection
     */
    public function setLogUseMainConnection(bool $log_use_main_connection): void
    {
        $this->_log_use_main_connection = $log_use_main_connection;
    }

    /**
     * @param null $log_connection
     */
    public function setLogConnection($log_connection): void
    {
        $this->_log_connection = $log_connection;
    }

    /**
     * @param string $log_destination
     */
    public function setLogDestination(string $log_destination): void
    {
        $this->_log_destination = $log_destination;
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
     * @return bool
     */
    public function isLogUseMainConnection(): bool {
        return $this->_log_use_main_connection;
    }

    /**
     * @return null|LogConnection
     */
    public function getLogConnection(): ?LogConnection{
        if($this->_log_connection === null){
            $this->_log_connection = new LogConnection();
        }

        return $this->_log_connection;
    }

    /**
     * @return null|File
     */
    public function getFile(): ?File{
        if($this->_file === null){
            $this->_file = new File();
        }

        return $this->_file;
    }
}