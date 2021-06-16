<?php

//cargar la sesión de php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

const DS = DIRECTORY_SEPARATOR; //definir el separador de directorio
define('ROOT', str_replace('\\', DS, dirname(__FILE__)));
define('START_TIME', microtime(TRUE));


require ROOT . DS . 'config' . DS . 'config.php';
require ROOT . DS . 'config' . DS . 'bootstrap.php';
