<?php

class DefaultService implements ServiceInterface
{
	public function service(string $command, $template)
	{
		$cmd = new $command();
		
		$template->use('default');
		
		$cmd->execute($template);
		
	}	
}
