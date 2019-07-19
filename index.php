<?php
require_once __DIR__ . '/vendor/autoload.php';

use Database\Database;

$database = Database::getInstance([
        'config' => [
            'debug' => true, //true = do not send the Query to server
            'timer' => true, //true = save the sql execution time
            'log' => [
                'enabled' => true, // true = enabled the log functions
                'destination' => 'all', //file = only in log file, database = only in database, all = file and database
                'echo' => true, // prints the Query
                'file' => [
                    'log_path' => '/Log/Query.log', // Path for file log
                ],
                'database' => [ //Database config to store the logs into a table
                    'connection_data' => [
                        'main_host' => true, //true use the main connection_data, false use the following connection_data
                        'host' => 'h2616533.stratoserver.net',
                        'user' => 'aaa807479cce7a30',
                        'pass' => '4Hodf#81',
                        'prefix' => '',
                        'database' => '77038d8a13b981477fd896a87e57bbb962c0a0dc',
                        'port' => '3306',
                        'table' => 'log',
                        'charset' => 'utf8',
                        'timezone' => 'Europe/Berlin',
                    ],
                ],
            ]
        ],
        'connection_data' => [
            'host' => 'h2616533.stratoserver.net',
            'user' => 'aaa807479cce7a30',
            'pass' => '4Hodf#81',
            'prefix' => '',
            'database' => '77038d8a13b981477fd896a87e57bbb962c0a0dc',
            'port' => '3306',
            'table' => 'log',
            'charset' => 'utf8',
            'timezone' => 'Europe/Berlin',
        ]
    ]
);

$database->insert()->addTable('components');
$database->insert()->addFields(['co_id', 'co_id', 'co_id']);
$database->insert()->addValues(['co_id', 1, 'co_id']);
echo '<pre>';
    print_r($database->execute());
echo '</pre>';

