<?php
class LoginCommand implements CommandInterface
{
	public function execute($template)
	{
		$template->set('view', 'login/default');
		$template->use('login');
		

		$user = Request::post('user');
		$pass = md5(Request::post('pass'));
		
		$template->set('login', $user);

		if (!empty($user) && !empty($pass)) {
			//validar acceso contra la base de datos
			$reader = new Db();
			$sql = (new QueryBuilder())
				->table('usuario')
				->condition('login = :login AND clave = :clave')
				->limit(1)
				->select();
			
			$result = $reader->all($sql, [':login' => $user, ':clave' => $pass]);
			
			if (! empty($result) ) {
				//sesion correcta
				Session::set('is_auth', true);
				Session::set('uid', $result[0]['id']);
				Session::set('uname', $result[0]['nombre']);
			
				Redirect::to();
				return;
			}
		}

	}
}
