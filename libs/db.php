<?php
class Db 
{
	private $_conn = null;
    private $_config = [];
    private $_lastId;
    private $_affectedRows;

    public function __construct() {
        $this->_config = require ROOT . DS . 
        				'config' . DS . 'databases.php';
    }
    
    public final function connect(string $database = 'default') {
        try {
            $this->_conn = new PDO(
            	$this->_config[$database]['dsn'], 
            	$this->_config[$database]['username'], 
            	$this->_config[$database]['password'], 
            	$this->_config[$database]['params']
            	);
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
    public final function find(string $sql, array $parameters = null) {
        $this->connect();

        $sth = $this->_conn->prepare($sql);
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

    public final function find_first(string $sql, array $parameters = null) {
        $this->connect();
        $sth = $this->_conn->prepare($sql);
        if (is_array($parameters)) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }
        $result = $sth->fetch(PDO::FETCH_ASSOC);
        $sth->closeCursor();
        $this->close();

        return $result;
    }

    public final function select_one(string $sql)
    {
        $this->connect();
        $sth = $this->_conn->prepare($sql);
        $sth->execute();
        $result = $sth->fetch(PDO::FETCH_NUM);
        $sth->closeCursor();
        $this->close();

        return !empty($result) ? $result[0] : null;
    }    


    public final function exec(string $sql, array $parameters = null) {
        $this->connect();
        $sth = $this->_conn->prepare($sql);
        
        if (is_array($parameters)) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }

        $this->_lastId = null;
        $this->_affectedRows = null;
        
        $this->_affectedRows = $sth->rowCount();
        $result = intval($this->_affectedRows) > 0;
        
        if (strpos(strtolower($sql), 'insert into') !== false) {
            $this->_lastId = $this->_conn->lastInsertId();
            $result = $this->_lastId;
        }
        
        $this->close();
        
        return $result ?? null;
    }

    public final function getLastId()
    {
        return $this->_lastId;
    }

    public final function close() {
        $this->_conn = null;
    }

    public final function begin() {
        $this->_conn->beginTransaction();
    }

    public final function commit() {
        $this->_conn->commit();
    }

    public final function rollback() {
        $this->_conn->rollback();
    }
}
