<?php

class Module_Page extends Module_Index
{
	public function __construct($url) {
		parent::__construct($url);

		if (empty($url[2]) || !is_numeric($url[2])) {
			$this->id = false;
		} else {
			$this->id = $url[2];
		}
	}
}
