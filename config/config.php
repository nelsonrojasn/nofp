<?php

//establecer el directorio público (donde dejar los css, js, e imágenes)
define('PUBLIC_PATH', '/public/');
define('BASE_PATH', '/');
define('PROJECT_NAME', 'sistema');
define('SAFETY_SEED', md5('asdfgt67uyhn@#@ji9ijuhtTGJK&y$53KgT12a'));

//*Locale*
setlocale(LC_ALL, 'es_CL');

//*Timezone*
ini_set('date.timezone', 'America/Santiago');

//*error reporting*
error_reporting(E_ALL ^ E_STRICT);
ini_set('display_errors', 'On');
