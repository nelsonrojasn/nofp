<?php

class Security {

    public static function injectAntiCSRFHeader() {
        // Cross-Origin Resource Sharing Header
        header('Access-Control-Allow-Origin: http://127.0.0.1');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: X-Requested-With, Content-Type, Accept');
    }

    public static function sanitize($input, $filter = FILTER_SANITIZE_STRING) {
        $result = trim($input);
        $result = stripslashes($result);
        $result = strip_tags($result);
        $result = htmlspecialchars($result);

        if ($filter !== NULL) {
            $result = filter_var($result, $filter);
        }

        return $result;
    }

    public static function getHashKey($key)
    {
        return $key . '.' . md5($key . SAFETY_SEED);
    }

    public static function isValidKey($hash)
    {
        $elements = explode('.', $hash);

        if (strcmp(md5($elements[0] . SAFETY_SEED), $elements[1]) == 0) {
            return true;
        } else {
            return false;
        }
    }

    public static function getKeyValue($hash)
    {
        $elements = explode('.', $hash);

        return intval($elements[0]);
    }

}
