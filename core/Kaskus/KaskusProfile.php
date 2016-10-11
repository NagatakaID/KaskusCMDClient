<?php

namespace Kaskus\Core\Kaskus;

use Kaskus\Core\HttpClient;
use Kaskus\Core\XPathParser;
use Kaskus\Core\App;

class KaskusProfile{

	protected $xpath;
	protected $username;
	protected $jobTitle;
	protected $userId;
	protected $memberSince;
	protected $totalPosts;

	protected $xpathjobtitle = '//div[@class="author"]';
	protected $xpathgroupmeta = '//div[@class="group-meta"]/div/b';

	public function __construct () {
		HttpClient::init();
		$this->username = App::get('config')['username'];
		$this->xpath = new XPathParser;
	}

	public function getProfileInfo()
	{
		$body = HttpClient::get('http://www.kaskus.co.id/profile/aboutme')->getBodyContent();
		$this->jobTitle = $this->xpath->GetContent($body, $this->xpathjobtitle);
		$temp = $this->xpath->GetContents($body,$this->xpathgroupmeta);
		$this->userId = $temp[0];
		$this->memberSince = $temp[1];
		$this->totalPosts = $temp[2];
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
}