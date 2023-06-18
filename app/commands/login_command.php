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
			$sql = new SqlBuilder();

			$sql->addTable('usuario');
			$sql->addWhat(['conditions: login = :login AND clave = :clave', 'limit: 1']);
			
			$result = Db::findFirst($sql, [':login' => $user, ':clave' => $pass]);
			
			if (! empty($result) ) {
				//sesion correcta
				Session::set('is_auth', true);
				Session::set('uid', $result['id']);
				Session::set('uname', $result['nombre']);
			
				Redirect::to();
				return;
			}
		}

	}
}
