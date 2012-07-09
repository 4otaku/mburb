<?php

class Module_Add extends Module_Abstract_Authorized
{
	protected $css = array('add');
	protected $js = array('add');

	protected function get_data() {
		return array(
			'files' => Database::order('time')->get_full_vector('file')
		);
	}
}
