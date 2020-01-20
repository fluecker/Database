<?php
require_once 'vendor/autoload.php';

use Database\Database;

$database = new Database(
    [
        '',
        '',
        '',
        '',
        'port' => '3306',
        'charset' => 'utf8',
    ],
    null,
    [
        'debug' => true,
        'time' => true,
        'log' => true
    ]
);

$database->select()->addFields(['*'])->addFrom('components')->addWhere([['co_state', 1]]);
$database->select()->addWhere()->addNotBetween('co_id', 1, 2);
$database->select()->addWhere()->addNotLike('co_id', 'jfoaisdf%');
$database->select()->addWhere()->addIsNotNull('co_id');

echo '<pre>';
print_r($database->execute());
echo '</pre>';
