<?php

namespace Kaskus\Core;

use XPathSelector\Selector;

class XPathParser{

	protected $xpath;

	public function GetContent($source, $xpathquery)
	{
		$result = [];
		$this->xpath = Selector::loadHTML($source);
		if(is_array($xpathquery))
		{
			foreach ($xpathquery as $value) {
				$result[] = $this->xpath->find($xpathquery)->innerHTML();
			}
		}
		else
		{
			$result = $this->xpath->find($xpathquery)->innerHTML();
		}
		return $result;
	}

	public function GetContents($source, $xpathquery)
	{
		$result = [];

		$this->xpath = Selector::loadHTML($source);

		$hasil = $this->xpath->findAll($xpathquery);

		foreach ($hasil as $value) {
			$result[] = $value;
		}

		return $result;
	}

}