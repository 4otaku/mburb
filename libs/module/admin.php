<?php

class Module_Admin extends Module_Abstract_Authorized
{
	protected $css = array('admin');
	protected $js = array('admin');

	protected function get_data() {
		return array(
			'strips' => Database::order('order', 'asc')->get_full_table('strip')
		);
	}
}
