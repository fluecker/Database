<?php
require_once __DIR__ . '/vendor/autoload.php';

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
        'o.o_id', 'o_r_id', 'o_u_id', 'o_order_state', 'o_send_datetime', 'o_sum_value', 'o_isTestOrder',
        'o_pdf_created', 'r_name', 'o_new_id', 'r_new_id', 'o_u_address', 'o_isRated', 'o_mobilOrder', 'pm_display_name'
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
        ['o_send_datetime', 'DESC']
    ]
);

echo '<pre>';
    print_r($database->execute());
echo '</pre>';

