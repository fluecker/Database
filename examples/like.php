<?php
include 'config.php';

$database->select()->addFields(['*'])->addFrom('table')->addWhere([['value', 1]]);
$database->select()->addWhere()->addLike('id', 'expression');

echo '<pre>';
print_r($database->execute());
echo '</pre>';

$database->select()->addFields(['*'])->addFrom('table')->addWhere([['value', 1]]);
$database->select()->addWhere()->addNotLike('co_id', 'jfoaisdf%');

echo '<pre>';
print_r($database->execute());
echo '</pre>';