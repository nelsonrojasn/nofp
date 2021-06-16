<?php

class Redirect
{

    /**
     */
    public static function to($route)
    {
        header('Location: ' . $route, TRUE, 302);
    }
}
