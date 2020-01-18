<?php
include 'config.php';

$database->delete()->addFrom('table')->addWhere(
    [
        ['name', 'test']
    ]
);

$database->execute();