<?php

namespace Kaskus\Core\Kaskus;

use Kaskus\Core\HttpClient;
use Kaskus\Core\App;
class KaskusLogin{

	protected $username;
	protected $password;
	protected $md5password;
	protected $isLogin;

	public function __construct () {
		$this->username = App::get('config')['username'];
		$this->password = App::get('config')['password'];
		$this->md5password = md5(App::get('config')['password']);
	}

	public function login()
	{
		$body = HttpClient::post('https://www.kaskus.co.id/user/login', [
			    'form_params' => [
			        'username' => $this->username,
			        'md5password' => $this->md5password,
			        'md5password_utf' => $this->md5password,
			        'password' => ''
			    ]
			]
		)->getBodyContent();

		if (strpos($body, 'Thank you for') !== FALSE)
		{
			$this->isLogin = true;

		}
		else
		{
			$this->isLogin = false;
		}

		return $this->isLogin;
	}

	public function isLoggedIn()
	{
		return $this->isLogin;
	}
}