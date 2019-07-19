<?php
namespace Database\Interfaces;


interface Part {
    public function addWhere(array $where);
    public function toSql(string $function):string;
}