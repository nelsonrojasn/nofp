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
		$sql = (new QueryBuilder())->table('usuario')->limit(1)->select();

		$result = $reader->all($sql);
		
		$template->set('usuarios', $result);
		$template->set('view', 'default/default');
		
	}
}
