<?php

namespace Kaskus\Core\Kaskus;

use Kaskus\Core\HttpClient;
use Kaskus\Core\XPathParser;

class KaskusPost{

	protected $threadLinks;
	protected $xpath;
	protected $xpathtitle = '//div[@class="current"]';
	protected $xpaththreadid = '//input[@id="thread_id"]/@value';
	protected $xpathsecuritytoken = '//div[@class="main-content"]/input[@class="sctoken"]/@value';

	public function __construct ($threadLinks) {
		$this->threadLinks = $threadLinks;
		$this->xpath = new XPathParser;
	}

	public function postToThreads()
	{
		foreach ($this->threadLinks as $key => $value) {

			$body = HttpClient::get("http://www.kaskus.co.id/{$value}")->getBodyContent();
			$title = $this->xpath->GetContent($body,$this->xpathtitle);
			$thread_id = $this->xpath->GetContent($body,$this->xpaththreadid);
			$securitytoken = $this->xpath->GetContent($body,$this->xpathsecuritytoken);
			echo "Reply Thread : {$title}".PHP_EOL;
			$reply = fgets(STDIN);

			$body = HttpClient::post("http://www.kaskus.co.id/post_reply/{$thread_id}", [
				    'form_params' => [
				        'thread_id' => $thread_id,
				        'qr_page' => 1,
				        'post_id' => 'who cares',
				        'do' => 'postreply',
				        'fromquickreply' => '1',
				        'message' => $reply,
				        's' => '',
				        'securitytoken' => $securitytoken,
				        'parseurl' => '1',
				    ]
				]
			)->getBodyContent();

			if (strpos($body, 'Thank you for posting') !== FALSE)
			{
				echo "Posted Successfuly".PHP_EOL;
			}

		}
	}
}