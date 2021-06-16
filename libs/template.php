<?php
class Template
{
	private $_templateName;
	protected $_variables = [];
	
	public function use($templateName)
	{
		$this->_templateName = $templateName;
	}
	
	public function set($key, $value)
	{
		$this->_variables[$key] = $value;
	}
	
	public function get(string $key)
	{
		return $this->_variables[$key];
	}
	
	public function __construct($templateName = 'default')
	{
		$this->_templateName = $templateName;
	}	
	
	public function __destruct()
	{
		extract($this->_variables);
		
		$yield = '';

		if (!empty($view)){
			ob_start('ob_gzhandler');
			
			include ROOT . DS . 'app' . DS . 
					'views' . DS . $view . '.phtml';
		
			$yield = ob_get_clean();	
		}
		
		if (!empty($this->_templateName)) {
			include ROOT . DS . 'app' . DS . 
					'templates' . DS . $this->_templateName . '.phtml';
		} else {
			echo $yield;
		}
	}
}
