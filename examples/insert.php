<?php
include 'config.php';

$database->insert()->addTable('table');
$database->insert()->addFields(['name', 'age', 'update', 'state']);
$database->insert()->addValues(['test', 12, true, 0]);

$database->execute();
$id = $database->getLastInsertId();