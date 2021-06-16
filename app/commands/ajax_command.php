<?php
class AjaxCommand implements CommandInterface
{
	public function execute($template)
	{
		$template->use('plain');
		$template->set('view', 'default/ajax');
	}
}
