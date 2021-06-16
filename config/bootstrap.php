<?php

spl_autoload_register(
    function($className) {
        $file = from_camel_case(trim($className)) . '.php';
        $findOn = [ROOT . DS . 'libs' . DS, 
        		   ROOT . DS . 'app' . DS . 'commands' . DS,
        		   ROOT . DS . 'app' . DS . 'interfaces' . DS,
        		   ROOT . DS . 'app' . DS . 'services' . DS, 
                   ROOT . DS . 'app' . DS . 'filters' . DS	];
        
        foreach($findOn as $f) {
        	if (file_exists($f. $file)) {
		        require_once $f. $file;
		    }
        }
    }
);


set_exception_handler(
	function ($ex) {
	    error_log("Uncaught exception class=" . get_class($ex) . " message=" . $ex->getMessage() . " line=" . $ex->getLine());
	    ob_end_clean(); # try to purge content sent so far
	    header('HTTP/1.1 500 Internal Server Error');
	    //header("Location: " . PUBLIC_PATH . "500",TRUE,307);

        $template = new Template();
        $template->use('error');
        $template->set('view', '');
        $template->set('message', 
            "Uncaught exception class=" . get_class($ex) . 
            " message=" . $ex->getMessage() . 
            " line=" . $ex->getLine() . PHP_EOL . 
            $ex->getMessage());

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
function from_camel_case($input) {
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


function dispatch() 
{

    //reconocer pagina seleccionada y accion
    $url = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '/';

    Logger::debug($url);


    $content = explode('/', $url);
    array_shift($content); //quitar el elemento inicial vacio
    
    $service = toPascalCase(!empty($content[0]) ? trim($content[0]) : 'default');
    $service .= 'Service'; 
    array_shift($content); //mover el arreglo
    
	$command = toPascalCase(!empty($content[0]) ? trim($content[0]) : 'default');
	$command .= 'Command';
	
	$template = new Template();
	

    
    $reflectSrv = new ReflectionClass($service); //cargamos la clase por reflexion
    $srv = $reflectSrv->newInstance(); //creamos la instancia
    
    
    $reflectCmd = new ReflectionClass($command);
    $cmd = $reflectCmd->newInstance(); //creamos la instancia
    
    if (!empty($reflectSrv->getShortName())) {
        $srv->service($cmd, $template);    
    } else {
        throw(new Exception("$service not found", 1));
    }
            
    

}


/**
 * sanitizar los elementos que pudieran haberse ingresado
 */
removeMagicQuotes();
unregisterGlobals();

/**
 * llamar al despachador
 */
dispatch();
