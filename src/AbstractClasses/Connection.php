<?php
namespace AbstractClasses;


abstract class Connection extends ObjectAbstract {
    protected $_host = '';
    protected $_username = '';
    protected $_password = '';
    protected $_prefix = '';
    protected $_database = '';
    protected $_port = 3306;
    protected $_charset = 'utf8';
    protected $_timezone = 'Europe/Berlin';

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->_host;
    }

    /**
     * @param string $host
     */
    public function setHost(string $host): void
    {
        $this->_host = $host;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->_username;
    }

    /**
     * @param string $user
     */
    public function setUsername(string $user): void
    {
        $this->_username = $user;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->_password;
    }

    /**
     * @param string $pass
     */
    public function setPassword(string $pass): void
    {
        $this->_password = $pass;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->_prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix(string $prefix): void
    {
        $this->_prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getDatabase(): string
    {
        return $this->_database;
    }

    /**
     * @param string $database
     */
    public function setDatabase(string $database): void
    {
        $this->_database = $database;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->_port;
    }

    /**
     * @param int $port
     */
    public function setPort(int $port): void
    {
        $this->_port = $port;
    }

    /**
     * @return string
     */
    public function getCharset(): string{
        return $this->_charset;
    }

    /**
     * @param string $charset
     */
    public function setCharset(string $charset): void
    {
        $this->_charset = $charset;
    }

    /**
     * @return string
     */
    public function getTimezone(): string
    {
        return $this->_timezone;
    }

    /**
     * @param string $timezone
     */
    public function setTimezone(string $timezone): void
    {
        $this->_timezone = $timezone;
    }

    public function __construct($data = null) {
        if($data !== null) {
            foreach ($data as $key => $value) {
                $prop = '_' . $key;
                $this->$prop = $value;
            }
        }
    }
}