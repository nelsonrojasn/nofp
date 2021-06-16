<?php

class Redirect
{

    /**
     */
    public static function to($route)
    {
        header('Location: ' . BASE_PATH . $route, TRUE, 302);
    }
}
