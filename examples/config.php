<?php

require_once '../vendor/autoload.php';

use Database\Database;

$database = Database::getInstance([
        'config' => [
            'debug' => false, //true = do not send the Query to server
            'timer' => true, //true = save the sql execution time
            'log' => [
                'enabled' => true, // true = enabled the log functions
                'destination' => 'all', //file = only in log file, database = only in database, all = file and database
                'echo' => true, // prints the Query
                'file' => [
                    'log_path' => dirname(__DIR__) . '/Log', // full path to your logfile
                    'log_file' => 'Query.log', // Path for file log
                ],
                'database' => [ //Database config to store the logs into a table
                    'connection_data' => [
                        'main_host' => true, //true use the main connection_data, false use the following connection_data
                        'host' => '',
                        'user' => '',
                        'pass' => '',
                        'prefix' => '',
                        'database' => '',
                        'port' => '3306',
                        'charset' => 'utf8',
                        'timezone' => 'Europe/Berlin',
                    ],
                    'table_data' => [ //log table
                        'name' => '', //log table name
                        'columns' => [], //log table columns
                        'values' => [] //log table values
                    ],
                ],
            ]
        ],
        'connection_data' => [
            'host' => '',
            'user' => '',
            'pass' => '',
            'prefix' => '',
            'database' => '',
            'port' => '3306',
            'charset' => 'utf8',
            'timezone' => 'Europe/Berlin',
        ]
    ]
);