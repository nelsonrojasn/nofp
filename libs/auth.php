<?php

class Auth
{

    private $tblName;

    private $loginField;

    private $pwdField;

    public function __construct($tableName, $loginField, $pwdField)
    {
        $this->tblName = $tableName;
        $this->loginField = $loginField;
        $this->pwdField = $pwdField;
    }

    public static function get($key)
    {
        if (Session::has($key)) {
            return Session::get($key);
        } else {
            return null;
        }
    }

    public function check($username, $userpassword)
    {
        $result = FALSE;

        $db = new Db();
        $sql = ' SELECT * FROM ' . $this->tblName . ' WHERE ' . $this->loginField . ' = :login ' . ' AND ' . $this->pwdField . '= :password';
        $row = $db->first($sql, [
            ':login' => $username,
            ':password' => $userpassword
        ]);
        if ($row) {
            $result = TRUE;
            foreach ($row as $key => $value) {
                Session::set($key, $value);
            }
            
            Session::set('fecha_servidor', date("d-m-Y"));
            Session::set('logo', PUBLIC_PATH . 'img/logow.png');
            Session::set('gravatar', "http://www.gravatar.com/avatar/" . md5(strtolower(trim($row['email']))) . '?d=' . urlencode('mm') . '&s=' . '40');
        } else {
            Logger::debug('Not found');
        }

        return $result;
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function isLoggedIn()
    {
        $result = FALSE;
        if (static::get('id')) {
            if (intval(static::get('id')) > 0) {
                $result = TRUE;
            }
        }
        
        Logger::info('tiene sesion ' . $result ? 'Si' : 'No');
        
        return $result;
    }

    public static function isAdmin()
    {
        $result = FALSE;
        if (static::get('id')) {
            if (intval(static::get('admin')) > 0) {
                $result = TRUE;
            }
        }
        return $result;
    }
}
