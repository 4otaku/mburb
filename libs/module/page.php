<?php

class Module_Page extends Module_Abstract_Html
{
	protected $css = array('main');
	protected $js = array('external/cookie', 'main');
	protected $id;

	public function __construct($url) {
		parent::__construct($url);

		if (empty($url[2]) || !is_numeric($url[2])) {
			$this->id = false;
		} else {
			$this->id = $url[2];
		}
	}

	protected function get_data() {
		if (!$this->id) {
			return array('error' => true);
		}
		$strip = Database::get_full_row('strip', '`order` = ?', $this->id);
		if (empty($strip)) {
			return array('error' => true);
		}
		$strip['text'] = Transform_Text::format($strip['text']);

		$logs = Database::order('order')->limit(30)->get_vector(
			'strip', array('id', 'title', 'date'), '`order` > 0');

		foreach ($logs as &$log) {
			$log['date'] = strtotime($log['date']);
			$log['date'] = $log['date'] > 0 ? date('y/m/d', $log['date']) : '';
		}

		return array(
			'page' => $this->id,
			'logs' => $logs,
			'strip' => $strip,
			'images' => Database::join('file', 'sf.id_file = f.id')->
				order('sf.order', 'asc')->get_full_table('strip_file', 'id_strip = ?', $this->id),
			'next' => Database::order('order', 'asc')->get_full_row('strip', '`order` > ?', $strip['order']),
			'back' => Database::order('order')->get_field('strip', 'order', '`order` < ?', $strip['order']),
			'autosave' => isset($_COOKIE['autosave']) ? (int) $_COOKIE['autosave'] : 0
		);
	}
}
