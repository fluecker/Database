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
                    'log_path' => dirname(__DIR__) . '/Database/src/Log', // full path to your logfile
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

//Is working
//$database->insert()->addTable('components');
//$database->insert()->addFields(['co_name', 'co_namespace', 'co_display_name', 'co_state', 'co_rights', 'co_id', 'co_underline']);
//$database->insert()->addValues(['test', 'test\test', 'TestComponente', 0, 0, 999999, 'test underline']);

//Is Working
$database->select()->addFields(['*'])->addFrom('components')->addWhere([['co_state', 1]]);
$database->select()->addWhere()->addNotBetween('co_id', 1, 2);
$database->select()->addWhere()->addNotLike('co_id', 'jfoaisdf%');
$database->select()->addWhere()->addIsNotNull('co_id');

//$database->select()->addFields(['*'])->addFrom('components')->addWhere([['co_name', 'rest']]);

//Is Working
//$database->update()->addTable('components')->addFields([['co_namespace', 'test\rest']])->addWhere([['co_name', 'test']]);

//Is working
//$database->delete()->addFrom('components')->addWhere([['co_name', 'test']]);

echo '<pre>';
print_r($database->execute());
echo '</pre>';

