<?php
/**
 * QueryBuilder
 */
class QueryBuilder {

    protected $_table = '';
    protected $_columns = '';
    protected $_conditions = '';
    protected $_joins = '';
    protected $_limit = '';
    protected $_group = '';
    protected $_having = '';
    protected $_orderBy = '';
    protected $_result = [];
    protected $_queries = [];

    /**
     * table
     * @params table
     */
    public function table($tableName) {
        $this->_table = $tableName;
        return $this;
    }

    /**
     * columns
     * @params columns
     */
    public function columns($columns) {
        $this->_columns = $columns;
        return $this;
    }

    /**
     * condition
     * @params condition
     * @params operator
     */
    public function condition($condition, $operator = 'AND') {
        if (empty($this->_conditions)) {
            $this->_conditions = 'WHERE ' . $condition;
        } else {
            $this->_conditions .= $operator . ' ' . $condition;
        }

        $this->_conditions .= ' '; // extra space after condition
        return $this;
    }

    /**
     * join
     * @params join
     */
    public function join($join) {
        $this->_joins .= $join . ' ';
        return $this;
    }

    /**
     * limit
     * @params limit
     */
    public function limit($limit) {
        $this->_limit = 'LIMIT ' . $limit;
        return $this;
    }

    /**
     * group
     * @params group
     */
    public function group($group) {
        $this->_group = $group;
        return $this;
    }

    /**
     * having
     * @params having
     */
    public function having($having) {
        $this->_having = $having;
        return $this;
    }

    /**
     * orderBy
     * @params orderBy
     */
    public function orderBy($orderBy) {
        $this->_orderBy = ' ORDER BY ' . $orderBy;
        return $this;
    }

    /**
     * select
     */
    public function select() {
        $sql = 'SELECT ' . (empty($this->_columns) ? '*' : $this->_columns) . 
        	   ' ' . 'FROM ' . $this->_table . ' ';

        $check_for = [
            '_joins',
            '_conditions',
            '_group',
            '_having',
            '_limit',
            '_orderBy'
        ];

        foreach ($check_for as $element) {
            if (!empty(trim($this->$element))) {
                $sql .= ' ' . $this->$element . ' ';
            }
        }

        return $sql;
    }
    
    /**
     * insert
     * @params params
     */
    public function insert($params) {
        $fields = '';
        $values = '';

        foreach ($params as $key => $value) {
            $fields .= $key . ',';
            $values .= ':' . $key . ',';
        }
        $fields = str_replace(',,', '', $fields . ',');
        $values = str_replace(',,', '', $values . ',');

        $sql = "INSERT INTO {$this->_table} ({$fields}) VALUES ({$values});";
        return $sql;
    }

    /**
     * update
     * @params conditions
     * @params params
     */
    public function update($conditions, $params) {
        $values = '';
        
        foreach ($params as $key => $value) {
            $values .= $key . '= :' . $key . ',';
        }
        $values = str_replace(',,', '', $values . ',');

        $sql = "UPDATE {$this->_table} SET {$values} WHERE {$conditions}";

        return $sql;
    }

    /**
     * delete
     * @params conditions
     */
    public function delete($conditions) {
        $sql = "DELETE FROM {$this->_table} WHERE {$conditions}";

        return $sql;
    }

    /**
     * clear
     */
    private function clear() {
        $clear_in = [
            '_table',
            '_joins',
            '_conditions',
            '_group',
            '_having',
            '_limit',
            '_orderBy'
        ];
        foreach ($clear_in as $elem) {
            $this->$elem = '';
        }

        $cuentaMax = count($this->_queries);

        for ($i = 0; $i < $cuentaMax; $i++) {
            unset($this->_queries [$i]);
        }
        $this->_queries = [];
    }

    /**
     * destruct
     */
    public function __destruct() {
        
        $this->clear();
    }

}
