<?php

use Kaskus\Core\App;
use Kaskus\Core\Kaskus\KaskusLogin;
use Kaskus\Core\Kaskus\KaskusThread;
use Kaskus\Core\Kaskus\KaskusPost;
use Kaskus\Core\Kaskus\KaskusProfile;
use Kaskus\Core\HttpClient;

require "vendor/autoload.php";

App::bind("config", require "config/config.php");

HttpClient::init();

$kaskusLogin = new KaskusLogin;
$kaskusLogin->login();
if($kaskusLogin->isLoggedIn())
{
	$profile = new KaskusProfile;
	$profile->getProfileInfo();

	echo "Username : {$profile->getUsername()}".PHP_EOL;
	echo "Title : {$profile->getJobTitle()}".PHP_EOL;
	echo "User Id : {$profile->getUserId()}".PHP_EOL;
	echo "Member Since : {$profile->getMemberSince()}".PHP_EOL;
	echo "Total Posts : {$profile->getTotalPosts()}".PHP_EOL;
	
	echo "".PHP_EOL;

	$kaskusThread = new KaskusThread;
	$kaskusThread->getThreads();
	$kaskusPost = new KaskusPost($kaskusThread->getThreadLink());
	$kaskusPost->postToThreads();
}