<?php

include 'config.php';

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

