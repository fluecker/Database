<?php
include 'config.php';

$database->select()->addFields(['*'])->addFrom('table')->addWhere(
    [
        ['state', 1]
    ]
);

$database->select()->addWhere()->addNotBetween('id', 1, 2);
$database->select()->addWhere()->addNotLike('id', 'test%');
$database->select()->addWhere()->addIsNotNull('id');


echo '<pre>';
print_r($database->execute());
echo '</pre>';