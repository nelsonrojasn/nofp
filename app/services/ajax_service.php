<?php

class AjaxService implements ServiceInterface
{
	public function service($command, $template)
	{
		$template->use('');
		
		$command->execute($template);
		
	}	
}
