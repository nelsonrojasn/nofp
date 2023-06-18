<?php

class Request {

    public static function isAjax() {
        return (isset($_SERVER ['HTTP_X_REQUESTED_WITH']) && $_SERVER ['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest');
    }

    public static function post($var) {
        return filter_has_var(INPUT_POST, $var) ? $_POST [$var] : NULL;
    }

    public static function hasPost($var) {
        return filter_has_var(INPUT_POST, $var);
    }

    public static function hasGet($var) {
        return filter_has_var(INPUT_GET, $var);
    }

    public static function get($var) {
        return filter_has_var(INPUT_GET, $var) ? $_GET [$var] : NULL;
    }

    public static function delete($var = '') {
        if ($var) {
            $_POST [$var] = array();
        } else {
            $_POST = array();
        }
    }

    public static function isSafe() {
        $result = filter_has_var(INPUT_POST, 'safetykey') ? $_POST ['safetykey'] : '';
        if (strlen($result) != 66) {
            return FALSE;
        }
        $resultMD5 = substr($result, - 33, - 1);

        if ($resultMD5 === md5(SAFETY_SEED)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
