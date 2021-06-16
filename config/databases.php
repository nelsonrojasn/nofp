<?php

$databases ['default'] = [
    'dsn' => 'mysql:host=localhost;port=3306;dbname=sistema',
    'username' => 'nelson',
    'password' => 's3cret',
    'params' => [
        PDO::ATTR_PERSISTENT => true, // conexión persistente
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
];


return $databases;
