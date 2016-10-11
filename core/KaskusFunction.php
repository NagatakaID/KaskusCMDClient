<?php
namespace Kaskus\Core;

use GuzzleHttp\Client;
use XPathSelector\Selector;

class KaskusFunction {

	protected $username;
	protected $password;
	protected $jobTitle;
	protected $userId;
	protected $memberSince;
	protected $totalPosts;
	protected $md5password;
	protected $client;
	protected $jar;
	protected $forumid;
	protected $totalthreadpage;
	protected $minimalpostpage;
	protected $threadLinks = [];

	public function __construct () {
		$config = App::get('config');
		$this->username = $config['username'];
		$this->password = $config['password'];
		$this->md5password = md5($config['password']);
		$this->client = new Client(['cookies' => true, 'verify' => false]);
		$this->forumid = $config['forumid'];
		$this->totalthreadpage = $config['totalthreadpage'];
		$this->minimalpostpage = $config['minimalpostpage'];
		$response = $this->client->get('https://www.kaskus.co.id/user/login');
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getJobTitle()
	{
		return $this->jobTitle;
	}

	public function getUserId()
	{
		return $this->userId;
	}

	public function getMemberSince()
	{
		return $this->memberSince;
	}

	public function getTotalPosts()
	{
		return $this->totalPosts;
	}

	public function login()
	{
		$response = $this->client->post('https://www.kaskus.co.id/user/login', [
			    'form_params' => [
			        'username' => $this->username,
			        'md5password' => $this->md5password,
			        'md5password_utf' => $this->md5password,
			        'password' => ''
			    ]
			]
		);

		$body = $response->getBody();

		if (strpos($body, 'Thank you for') !== FALSE)
		{
			return true;
		}
		else
		{
		 	return false;
		}
	}

	public function profilePage()
	{
		$response = $this->client->get('http://www.kaskus.co.id/profile/aboutme');
		$body = $response->getBody();
		
		$xpaths = Selector::loadHTML($body);
		
		$this->jobTitle = $xpaths->find('//div[@class="author"]')->innerHTML();
		
		$temp = $xpaths->findAll('//div[@class="group-meta"]/div/b');

		$this->userId = $temp->item(0)->innerHTML();
		$this->memberSince = $temp->item(1)->innerHTML();
		$this->totalPosts = $temp->item(2)->innerHTML();
	}

	public function getThreads()
	{
		echo "Get Threads.".PHP_EOL;
		for ($i=1; $i <= $this->totalthreadpage; $i++) { 
			$response = $this->client->get("http://www.kaskus.co.id/forum/{$this->forumid}/page/{$i}");
			$body = $response->getBody();
			$xpaths = Selector::loadHTML($body);
			$replies = $xpaths->findAll('//tr[starts-with(@id,"thread")][not(contains(@class,"sticky"))]/td[3]/div[@class="stats"]/a');
			$links = $xpaths->findAll('//tr[starts-with(@id,"thread")][not(contains(@class,"sticky"))]/td[2]/div/div[@class="post-title"]/a/@href');

			foreach ($links as $key => $link) {
				$reply = $replies->item($key);
				$tempReplies = explode(" ", $reply);
				if((int)$tempReplies[2] < (20 * $this->minimalpostpage))
				{
					continue;
				}
				$this->threadLinks[] = $link->innerHTML($key);
			}
		}
		echo "Crawling Thread Success".PHP_EOL;
		echo "Total : ".count($this->threadLinks).PHP_EOL;

	}

}