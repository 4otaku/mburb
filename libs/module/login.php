<?php

class Module_Login extends Module_Abstract_Authorized
{
	protected $js = array('login');

	protected $redirect_location = '/admin/';

	protected function get_user() {
		return parent::get_user() == false;
	}
}
