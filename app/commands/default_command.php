<?php
class DefaultCommand implements CommandInterface
{

	public function __construct()
	{
		$auth = new AuthFilter();
		$auth->execute();
	}

	public function execute($template)
	{
		$reader = new Db();
		$sql = new SqlBuilder();
		$sql->addTable('usuario');
		$sql->addWhat(['limit: 1']);

		$result = $reader->find_first($sql);
		
		$template->set('usuarios', $result);
		$template->set('timestamp', $reader->select_one('SELECT CURRENT_TIMESTAMP'));
		$template->set('view', 'default/default');
		
	}
}
