<?php 
class SqlBuilder {
    private $_from = [];
    private $_columns = [];
    private $_join = [];
    private $_conditions = [];
    private $_order = [];
    private $_limit = [];
    private $_function = '';
    private $_data = [];
    private $_fields = '';
    private $_values = '';

    private function _getParams(array $params)
    {
        $data = array();
        foreach ($params as $p) {
            if (is_string($p)) {
                $match = explode(': ', $p, 2);
                if (isset($match[1])) {
                    $data[$match[0]] = $match[1];
                } else {
                    $data[] = $p;
                }
            } else {
                $data[] = $p;
            }
        }

        return $data;
    }
    
    public function addTable(string $table)
    {
        $this->_from[] = $table;
    }

    public function addWhat(array $what)
    {
        $what = $this->_getParams($what);
        
        if (count($what) > 0) {
            foreach ($what as $key => $value) {
                array_push($this->{'_' . $key}, $value);
            }
        }
    }

    public function addFunction(string $function)
    {
        $this->_function = strtoupper($function);
    }
    
    public function addData(array $data)
    {
        $this->_data = $data;    
    }
    
    
    private function _generateColumnsFor(string $what, string $from, string $where)
    {
        if (strtoupper($what) == "INSERT") {
            $this->_fields = implode(', ', array_keys($this->_data));
            $this->_values = implode(', ', array_map(function($item) {
                return ':' . $item;
            }, array_keys($this->_data)));   
            return 'INSERT INTO ' . $from . ' (' .
                    $this->_fields . ') VALUES (' . $this->_values . ')'; 
        } 
        if (strtoupper($what) == "UPDATE") {
            $this->_values = implode(', ', array_map(function($item) {
                return $item . ' = :' . $item;
            }, array_keys($this->_data)));
            return 'UPDATE ' . $from . ' SET ' .
                    $this->_values . ' ' . $where;
        }
        if (strtoupper($what) == "DELETE") {
            return 'DELETE FROM ' . $from . ' ' . $where;
        }
    }
    
    
    private function _generate()
    {
        $from = implode(',', array_unique($this->_from));
        $where = implode(' AND ', array_unique($this->_conditions));
        $where = empty($where) ? '' : ' WHERE ' . $where;

        $join = implode(' ', array_unique($this->_join));
        $order = implode(' ', array_unique($this->_order));
        $order = empty($order) ? '' : ' ORDER BY ' . $order;
        
        $limit = implode(' ', array_unique($this->_limit));
        $limit = empty($limit) ? '' : ' LIMIT ' . $limit;

        $select = implode(',', array_unique($this->_columns));
        $select = empty($select) ? '*' : $select;


        if (!empty($this->_function)) {
            $result = $this->_generateColumnsFor($this->_function, $from, $where);
        } else {
            $result = 'SELECT ' . $select .
                      ' FROM ' . $from . ' ' . $join . ' ' .
                      $where . ' ' . $order . ' ' . $limit;
        }
        
        return $result;
    }
    
    private function _clear()
    {
        $this->_conditions = [];
        $this->_columns = ['*'];
        $this->_from = [];
        $this->_join = [];
        $this->_limit = [];
        $this->_order = [];
        $this->_function = '';
        $this->_data = [];
        $this->_fields = '';
        $this->_values = '';
    }
    
    public function __toString()
    {
        return $this->_generate();        
    }

    public function __destroy()
    {
        $this->_clear();
    }
    
}