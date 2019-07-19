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

$database->select()->addFields(
    [
        'o.o_id', 'o.o_r_id', 'o.o_u_id', 'o.o_order_state', 'o.o_send_datetime', 'o.o_sum_value', 'o.o_isTestOrder',
        'o.o_pdf_created', 'r.r_name', 'o.o_new_id', 'r.r_new_id', 'o.o_u_address', 'o.o_isRated', 'o.o_mobilOrder', 'pm.pm_display_name'
    ]
);
$database->select()->addFrom(
    [
        ['orders','o']
    ]
);
$database->select()->addLeftJoin(['restaurants', 'r'], ['o.o_r_id', 'r.r_new_id']);
$database->select()->addLeftJoin(['paymentmethods', 'pm'], ['o.o_payment_id', 'pm.pm_id']);
$database->select()->setOrder(
    [
        ['o.o_send_datetime', 'DESC']
    ]
);

$database->select()->addWhere([
        [
            ['o.o_send_datetime', 'date'],
            ['NOW()', 'adddate', '-1 DAY'],
            '>'
        ], [
            ['o.o_send_datetime', 'date'],
            ['NOW()', 'adddate', '+1 DAY'],
            '<'
        ]
    ]
);

$database->select()->addWhere()->addLike('co.co_id', '%3%');
$database->select()->addWhere()->addOr()->addLike([['co.co_id', '%3%'], ['co.co_id', '%dfs%']]);

$database->select()->addWhere()->addInStatement('co.co_id')->addSubSelect()->addFields(['co_id'])->addFrom('components')->addWhere([['co.co_id', 1]])->setLimit(30);
$database->select()->addWhere()->addInStatement('co.co_id', [1,2,3,4,56,7,8,9], true);

echo '<pre>';
    print_r($database->execute());
echo '</pre>';

