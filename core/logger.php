<?php

class Logger
{

    public static function debug($message = '')
    {
        static::write('debug', $message);
    }

    public static function error($message = '')
    {
        static::write('error', $message);
    }

    public static function info($message = '')
    {
        static::write('info', $message);
    }

    public static function write($type = 'debug', $message = '')
    {
        $arch = fopen(realpath(APP_PATH) . 'log' . DS . 'log_' . date('Y-m-d') . '.txt', 'a+');

        fwrite($arch, '[' . date('Y-m-d H:i:s.u') . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' 
        			. ' - ' . $type . ' ] ' . $message . "\n");
        fclose($arch);
    }
}