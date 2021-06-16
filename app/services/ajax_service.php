<?php

class AjaxService implements ServiceInterface
{
	public function service(string $command, $template)
	{
		$cmd = new $command();
		
		$template->use('');
		
		$cmd->execute($template);
		
	}	
}
