<?php
class LogoutCommand implements CommandInterface
{
	public function execute($template)
	{

		Session::delete('is_auth');
		Session::delete('uid');
		Session::delete('uname');
	
		return Redirect::to('');
		
	}
}
