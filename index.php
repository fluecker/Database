<?php
require_once __DIR__ . '/vendor/autoload.php';

use Database\Database;

$database = Database::getInstance([
        'debug' => true, //true = do not send the Query to server, default = false
        'timer' => false, //true = save the sql execution time, default = false
        'log' => true, //true = enable the log functions, default = false
        'num_rows' => false, // true = shows the num rows of query, default = true
        'log_destination' => 'all', //file = only in log file, database = only in database, all = file and database, default = file
        'echo' => true, // prints the Query, default = false
        'log_file_path' => dirname(__DIR__) . '/Database/src/Log', // full path to your logfile, default = /Log
        'log_file_name' => 'Query.log', // Path for file log, default = Query.log
        'log_table_name' => 'log',
        'log_table_columns' => [
            'l_origin', 'l_state', 'l_remoteAddr', 'l_refer', 'l_browser', 'l_user', 'l_logKind', 'l_path', 'l_class', 'l_method', 'l_queryString', 'l_errorMessage', 'l_create_at'
        ],
        'log_table_values' => [
            'testSeite', '1', '0.0.0.0', 'vorherige seite', 'chrome', 'ich', 'mysql', 'hier', 'da', 'die', '[time]', '[message]', date('Y-m-d H:i:s')
        ],
        'main_connection' => [
            'host' => 'h2616533.stratoserver.net',
            'user' => 'fluecker',
            'pass' => '(Domwsib4)',
            'prefix' => '',
            'database' => 'lieferdev',
            'port' => '3306',
            'charset' => 'utf8',
            'timezone' => 'Europe/Berlin',
        ],
        'log_use_main_connection' => true, //true use the main connection_data, false use the following connection_data
        'log_connection' => [
            'host' => 'h2616533.stratoserver.net',
            'user' => 'fluecker',
            'pass' => '(Domwsib4)',
            'prefix' => '',
            'database' => 'lieferdev',
            'port' => '3306',
            'charset' => 'utf8',
            'timezone' => 'Europe/Berlin',
        ],
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

$database->execute();

