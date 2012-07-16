<?php

class Module_Index extends Module_Page
{
	public function __construct($url) {
		parent::__construct($url);

		$this->id = Database::order('order', 'asc')
			->get_field('strip', 'order', '`order` > 0');
	}
}
