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
		$sql = new SqlBuilder();
		$sql->addTable('usuario');
		$sql->addWhat(['limit: 1']);

		$result = Db::findFirst($sql);
		
		$template->set('usuarios', $result);
		$template->set('timestamp', Db::getScalar('SELECT CURRENT_TIMESTAMP'));
		$template->set('view', 'default/default');
		
	}
}
