<?php

namespace Kaskus\Core;

use GuzzleHttp\Client;
use XPathSelector\Selector;

class HttpClient
{

	protected static $client = null;
	protected static $response = null;
	private static $_instance = null;

	public static function init()
	{
		if(static::$client == null)
		{
			static::$client = new Client(['cookies' => true, 'verify' => false]);
			self::$_instance = new self;
		}
	}

	public static function get($url)
	{
		static::$response = static::$client->get($url);
		return self::$_instance;
	}

	public static function post($url,$parameter)
	{
		static::$response = static::$client->post($url, $parameter);
		return self::$_instance;
	}

	public static function getBodyContent()
	{
		return static::$response->getBody();
	}
}