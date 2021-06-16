<?php
class AuthFilter implements FilterInterface
{
	public function execute()
	{
		if (Session::get('is_auth') !== true) {
			return Redirect::to('default/login');
		}
	}
}