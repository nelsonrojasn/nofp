<?php
class Load 
{
	public static function partial(string $partial, $vars = null)
	{
		if (isset($vars) && count($vars) > 0) {
			extract($vars, EXTR_OVERWRITE);
		}		
		include ROOT . DS . 'app' . DS . 
					'views' . DS . '_shared' . DS . 
					'partials' . DS . $partial . '.phtml';
	}
}
