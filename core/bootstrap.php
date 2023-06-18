<?php

spl_autoload_register(
    function($className) {
        $file = fromCamelCase(trim($className)) . '.php';
        $findOn = [CORE_PATH, 
                   APP_PATH . 'libs' . DS,
        		   APP_PATH . 'commands' . DS,
        		   APP_PATH . 'interfaces' . DS,
        		   APP_PATH . 'services' . DS, 
                   APP_PATH . 'filters' . DS	];
        
        foreach($findOn as $f) {
        	if (file_exists($f. $file)) {
		        require_once $f. $file;
		    }
        }
    }
);


set_exception_handler(
	function ($ex) {
        $message = "Uncaught exception " . get_class($ex) . 
                  " \n" . $ex->getMessage() . 
                  " \nOn " . $ex->getFile() .  
                  " \nLine " . $ex->getLine();
        error_log($message);
	    ob_end_clean(); # try to purge content sent so far
	    header('HTTP/1.1 500 Internal Server Error');
	    //header("Location: " . PUBLIC_PATH . "500",TRUE,307);

        $template = new Template();
        $template->use('error');
        $template->set('view', '');
        $template->set('message',$message);

	    return;


	}
);


function stripSlashesDeep($value) {
    if (is_array($value)) {
        $value = array_map('stripSlashesDeep', $value);
    } else {
        $value = stripslashes($value);
    }
    return $value;
}

/**
 * Revisa la existencia de Magic Quotes las remueve *
 */
function removeMagicQuotes() {
    $_GET = stripSlashesDeep($_GET);
    $_POST = stripSlashesDeep($_POST);
    $_COOKIE = stripSlashesDeep($_COOKIE);
}

/**
 * Revisa register globals y los remueve *
 */
function unregisterGlobals() {
    if (ini_get('register_globals')) {
        $array = array(
            '_SESSION',
            '_POST',
            '_GET',
            '_COOKIE',
            '_REQUEST',
            '_SERVER',
            '_ENV',
            '_FILES'
        );
        foreach ($array as $value) {
            foreach ($GLOBALS[$value] as $key => $var) {
                if ($var === $GLOBALS[$key]) {
                    unset($GLOBALS[$key]);
                }
            }
        }
    }
}

/**
 * @see 
 * https://stackoverflow.com/questions/1993721/how-to-convert-camelcase-to-camel-case
 * @param string $input
 * @return string
 */
function fromCamelCase($input) {
    $matches = null;
    preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
    $ret = $matches[0];
    foreach ($ret as &$match) {
        $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
    }
    return implode('_', $ret);
}

function toPascalCase($input) {
    return str_replace('_', '', ucwords($input, '_'));
}


function dispatch($url) 
{
    $content = explode('/', $url);
    //quitar el elemento inicial vacio
    array_shift($content); 
    
    $service = toPascalCase(!empty($content[0]) ? trim($content[0]) : Config::DEFAULT_SERVICE);
    $service .= 'Service'; 
    //mover el arreglo
    array_shift($content); 
    
	$command = toPascalCase(!empty($content[0]) ? trim($content[0]) : Config::DEFAULT_COMMAND);
	$command .= 'Command';
    //mover el arreglo
    array_shift($content); 
	
	$template = new Template();
    //pasar el resto de parámetros en caso de existir
    $template->set('content', $content); 
    
    //cargamos el Servicio por reflexion
    $reflectSrv = new ReflectionClass($service); 
    //creamos la instancia
    $srv = $reflectSrv->newInstance(); 
    
    //cargamos el Comando por reflexion
    $reflectCmd = new ReflectionClass($command); 
    //creamos la instancia
    $cmd = $reflectCmd->newInstance(); 
    
    //revisamos la existencia del servicio
    if (!empty($reflectSrv->getShortName())) { 
        //ejecutamos el método predeterminado pasando el objeto comando y el objeto template
        $srv->service($cmd, $template);         
    } else {
        throw(new Exception("Unable to dispatch", 1));
    }
}

