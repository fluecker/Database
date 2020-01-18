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
                        'host' => 'h2616533.stratoserver.net',
                        'user' => 'fluecker',
                        'pass' => '(Domwsib4)',
                        'prefix' => '',
                        'database' => 'lieferdev',
                        'port' => '3306',
                        'charset' => 'utf8',
                        'timezone' => 'Europe/Berlin',
                    ],
                    'table_data' => [
                        'name' => 'log',
                        'columns' => [
                            'l_origin', 'l_state', 'l_remoteAddr', 'l_refer', 'l_browser', 'l_user', 'l_logKind', 'l_path', 'l_class', 'l_method', 'l_queryString', 'l_errorMessage', 'l_create_at'
                        ],
                        'values' => [
                            'testSeite', '1', '0.0.0.0', 'vorherige seite', 'chrome', 'ich', 'mysql', 'hier', 'da', 'die', '[time]', '[message]', date('Y-m-d H:i:s')
                        ]
                    ],
                ],
            ]
        ],
        'connection_data' => [
            'host' => 'h2616533.stratoserver.net',
            'user' => 'fluecker',
            'pass' => '(Domwsib4)',
            'prefix' => '',
            'database' => 'lieferdev',
            'port' => '3306',
            'charset' => 'utf8',
            'timezone' => 'Europe/Berlin',
        ]
    ]
);