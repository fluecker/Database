<?php

$database->update()->addTable('table')->addFields(
    [
        ['name', 'test2']
    ]
)->addWhere(
    [
        ['name', 'test']
    ]
);


$database->execute();