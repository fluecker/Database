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
                        'user' => 'fluecker',
                        'pass' => '(Domwsib4)',
                        'prefix' => '',
                        'database' => 'lieferdev',
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

$database->select('index.php')->addFields(
    [
        'co_name', 'co_namespace', 'co_display_name', 'co_state', 'co_id', 'co_subnavof', 'co_order', 'co_icon'
    ]
)->addFields()->addDateInterval('co_id', '5 Days', 'co_date');

$database->select()->addWhere(
    [
        ['co_state', 1]
    ]
);

$database->select()->addWhere()->addOr(
    [
        ['co_state', ''],
        ['co_state', 1],
    ]
);

$database->select()->addWhere(
    [
        ['co_name', '', '!=']
    ]
);

$database->select()->addWhere()->addOr()->addIsNull(
    [
        ['co_id', true],
        ['co_id'],
    ]
);

$database->select()->addWhere()->addColumnComparison(
    [
        ['co_id', 'co_name', '<'],
        ['co_id', 'co_name'],
    ]
);

$database->select()->addWhere()->addDateComparison('co_date', 'co_id', '5 MINUTE', '<');

$database->select()->addFrom(
    [
        ['components', 'com']
    ]
);


$database->execute();

