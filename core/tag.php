<?php

class Tag
{

    public static function css($file)
    {
        return '<link href="' . PUBLIC_PATH . 'css' . DS . $file . '.css" rel="stylesheet">';
    }

    public static function js($file)
    {
        return '<script type="text/javascript" src="' . PUBLIC_PATH . 'js' . DS . $file . '.js"></script>';
    }

    public static function resolve($source)
    {
        return PUBLIC_PATH . $source;
    }

    public static function link($text, $to, $attributes = '')
    {
        return "<a href='" . PUBLIC_PATH . "$to' $attributes>$text</a>";
    }

    public static function img($url, $alt = '', $attributes = '')
    {
        return "<img src='" . PUBLIC_PATH . "img/$url' alt='$alt' $attributes />";
    }
}
