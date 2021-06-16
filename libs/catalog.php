<?php
class Catalog
{
	private $_variables = [];
	
	public function set(string $key, $value)
	{
		$this->_variables[$key] = $value;
	}
	
	public function get(string $key)
	{
		return $this->_variables[$key] ?? null;
	}
	
	public function all()
	{
		return $this->_variables;
	}
	
}
