<?php

class Module_Files extends Module_Abstract_Authorized
{
	protected $css = array('files');
	protected $js = array('external/upload', 'files');

	protected function get_data() {
		return array(
			'files' => Database::order('time')->get_full_vector('file')
		);
	}
}
