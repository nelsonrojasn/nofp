<?php

class Session {

    const SESSION = 'cftXzU8009.';

    /**
     * Crear o especificar el valor para un indice de la sesión
     * actual
     *
     * @param string $index        	
     * @param mixed $value        	
     * @param string $namespace        	
     */
    public static function set($index, $value, $namespace = 'default') {
        $_SESSION [self::SESSION] [PROJECT_NAME] [$namespace] [$index] = $value;
    }

    /**
     * Obtener el valor para un indice de la sesión
     *
     * @param string $index        	
     * @param string $namespace        	
     * @return mixed
     */
    public static function get($index, $namespace = 'default') {
        if (isset($_SESSION [self::SESSION] [PROJECT_NAME] [$namespace] [$index])) {
            return $_SESSION [self::SESSION] [PROJECT_NAME] [$namespace] [$index];
        }
    }

    /**
     * Elimina un indice
     *
     * @param string $index        	
     * @param string $namespace        	
     */
    public static function delete($index, $namespace = 'default') {
        unset($_SESSION [self::SESSION] [PROJECT_NAME] [$namespace] [$index]);
    }

    /**
     * Verifica si el indice esta cargado en sesión
     *
     * @param string $index        	
     * @param string $namespace        	
     * @return boolean
     */
    public static function has($index, $namespace = 'default') {
        return isset($_SESSION [self::SESSION] [PROJECT_NAME] [$namespace] [$index]);
    }

}
