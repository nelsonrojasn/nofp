<?php

class DefaultService implements ServiceInterface
{
	public function service($command, $template)
	{
		$template->use('default');
		
		$command->execute($template);
		
	}	
}
