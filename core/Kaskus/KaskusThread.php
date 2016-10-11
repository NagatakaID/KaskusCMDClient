<?php

namespace Kaskus\Core\Kaskus;

use Kaskus\Core\HttpClient;
use Kaskus\Core\XPathParser;
use Kaskus\Core\App;

class KaskusThread{

	protected $xpath;
	protected $forumid;
	protected $totalthreadpage;
	protected $minimalpostpage;
	protected $xpathreplies = '//tr[starts-with(@id,"thread")][not(contains(@class,"sticky"))]/td[3]/div[@class="stats"]/a';
	protected $xpaththreadlink = '//tr[starts-with(@id,"thread")][not(contains(@class,"sticky"))]/td[2]/div/div[@class="post-title"]/a/@href';


	public function __construct () {
		$this->forumid = App::get('config')['forumid'];
		$this->totalthreadpage = App::get('config')['totalthreadpage'];
		$this->minimalpostpage = App::get('config')['minimalpostpage'];
		$this->xpath = new XPathParser;
	}

	public function getThreads()
	{
		echo "Get Threads.".PHP_EOL;
		for ($i=1; $i <= $this->totalthreadpage; $i++) { 

			$body = HttpClient::get("http://www.kaskus.co.id/forum/{$this->forumid}/page/{$i}")->getBodyContent();
			$replies = $this->xpath->GetContents($body,$this->xpathreplies);
			$links = $this->xpath->GetContents($body,$this->xpaththreadlink);

			foreach ($links as $key => $link) {

				$reply = $replies[$key];
				$tempReplies = explode(" ", $reply);

				if((int)$tempReplies[2] < (20 * $this->minimalpostpage))
				{
					continue;
				}
				$this->threadLinks[ceil(((int)$tempReplies[2] / 20))] = $link;
			}
		}
		echo "Crawling Thread Success".PHP_EOL;
		echo "Total Threads : ".count($this->threadLinks).PHP_EOL;

	}

	public function getThreadLink()
	{
		return $this->threadLinks;
	}
}