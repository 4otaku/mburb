<?php

class Module_View extends Module_Abstract_Authorized
{
	protected $css = array('main');
	protected $js = array('main');
	protected $id;

	public function __construct($url) {
		parent::__construct($url);

		if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
			$this->redirect_location = '/admin/';
			$this->create_redirect();
		} else {
			$this->id = (int) $_GET['id'];
		}
	}

	protected function get_data() {
		$strip = Database::get_full_row('strip', $this->id);
		if (empty($strip)) {
			return array('error' => true);
		}
		$strip['text'] = Transform_Text::format($strip['text']);

		return array(
			'strip' => $strip,
			'images' => Database::join('file', 'sf.id_file = f.id')->
				order('sf.order', 'asc')->get_full_table('strip_file', 'id_strip = ?', $strip['id']),
			'next' => $strip['order'] ?
				Database::order('order', 'asc')->get_full_row('strip', '`order` > ?', $strip['order']) :
				false
		);
	}
}
