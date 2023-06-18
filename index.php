<?php

//cargar la sesión de php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

//definir el separador de directorio
const DS = DIRECTORY_SEPARATOR; 

define('ROOT', str_replace('\\', DS, dirname(__FILE__)));
//establecer el directorio público (donde dejar los css, js, e imágenes)
define('PUBLIC_PATH', '/public/');
define('BASE_PATH', '/');
define('CORE_PATH', ROOT . DS . 'core' . DS);
define('APP_PATH', ROOT . DS . 'app' . DS);
define('PROJECT_NAME', 'sistema');
define('SAFETY_SEED', md5('asdfgt67uyhn@#@ji9ijuhtTGJK&y$53KgT12a'));

//*Locale*
setlocale(LC_ALL, 'es_CL');

//*Timezone*
ini_set('date.timezone', 'America/Santiago');

//*error reporting*
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');

define('START_TIME', microtime(TRUE));

$url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';
//$url = isset($_GET['url']) ? $_GET['url'] : '/';

require CORE_PATH . 'bootstrap.php';

/**
 * sanitizar los elementos que pudieran haberse ingresado
 */
removeMagicQuotes();
unregisterGlobals();

/**
 * llamar al despachador
 */
dispatch($url);

