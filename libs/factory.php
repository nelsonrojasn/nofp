 <?php
 class Factory
 {
 	private $db;
	
	public function __construct($db)
	{
		$this->db = $db;
	}
	
	public function add($sql, $parameters) {
        Logger::debug($sql);
        Logger::debug(implode($parameters));

        $sth = $this->conn->prepare($sql);
        if (is_array($parameters)) {
            $sth->execute($parameters);
        } else {
            $sth->execute();
        }
        $result = $this->conn->lastInsertId();
        $sth->closeCursor();

        if ($result) {
            return intval($result);
        } else {
            return NULL;
        }
    }

    public function delete($tableName, $id) {
        $sql = "DELETE FROM {$tableName} WHERE id = :id";

        Logger::debug($sql);

        $sth = $this->conn->prepare($sql);
        $result = $sth->execute([
            ':id' => intval($id)
        ]);

        if ($result) {
            return $result;
        } else {
            return FALSE;
        }
    }
 }
