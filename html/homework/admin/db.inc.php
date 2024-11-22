<?php
//https://gist.github.com/psxjpm/d2a78331fe70ea88e67be5eaead3084c
require './db.php';
$configuration = [
    'servername' => 'mariadb',
    'username' => 'root',
    'password' => 'rootpwd',
    'dbname' => 'cw2-database'
];

return new DB(
    $configuration['servername'],
    $configuration['username'],
    $configuration['password'],
    $configuration['dbname'],
    3306
);