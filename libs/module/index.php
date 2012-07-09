<?php

class Module_Index extends Module_Abstract_Html
{
	protected $id = 1;
	protected $css = array('main');
	protected $js = array('main');

	protected function get_data() {
		if (!$this->id) {
			return array('error' => true);
		}
		$strip = Database::get_full_row('strip', '`order` = ?', $this->id);
		if (empty($strip)) {
			return array('error' => true);
		}
		$strip['text'] = Transform_Text::format($strip['text']);

		return array(
			'strip' => $strip,
			'images' => Database::join('file', 'sf.id_file = f.id')->
				order('sf.order', 'asc')->get_full_table('strip_file', 'id_strip = ?', $this->id),
			'next' => $strip['order'] ?
				Database::order('order', 'asc')->get_full_row('strip', '`order` > ?', $strip['order']) :
				false
		);
	}
}
