<?php
class Db 
{
	private $conn = null;
    private $config = [];

    public function __construct() {
        $this->config = require ROOT . DS . 
        				'config' . DS . 'databases.php';
    }
    
    public function connect($database = 'default') {
        try {
            $this->conn = new PDO(
            	$this->config[$database]['dsn'], 
            	$this->config[$database]['username'], 
            	$this->config[$database]['password'], 
            	$this->config[$database]['params']
            	);
            return TRUE;
        } catch (Exception $e) {
            echo $e;
            return FALSE;
        }
    }
    
    public function all($sql, $parameters = null) {
        $this->connect();

        $sth = $this->conn->prepare($sql);
        if (is_array($parameters)) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }

        $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        $this->close();

        return $result;
    }

    public function first($sql, $parameters = null) {
        $this->conn->connect();
        $sth = $this->conn->prepare($sql);
        if (is_array($parameters)) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        $this->close();

        return $result;
    }
    
    public function exec($sql, $parameters = null) {
        Logger::debug($sql);
        if (is_array($parameters)) {
            Logger::debug(join(',', $parameters));
        }        
        $result = NULL;
        $this->connect();
        $sth = $this->conn->prepare($sql);
        if (is_array($parameters)) {
            $sth->execute($parameters);
        } else {
            $result = $sth->execute();
        }
        $this->close();

        return $result;
    }

    public function close() {
        $this->conn = null;
    }

    public function begin() {
        $this->conn->beginTransaction();
    }

    public function commit() {
        $this->conn->commit();
    }

    public function rollback() {
        $this->conn->rollback();
    }
}
