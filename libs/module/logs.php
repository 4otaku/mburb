<?php

class Module_Logs extends Module_Abstract_Html
{
	protected $css = array('main');

	protected function get_data() {
		$logs = Database::order('order')->get_vector(
			'strip', array('id', 'order', 'title', 'date'), '`order` > 0');

		foreach ($logs as &$log) {
			$log['date'] = strtotime($log['date']);
			$log['date'] = $log['date'] > 0 ? date('y/m/d', $log['date']) : '';
		}

		return array(
			'logs' => $logs
		);
	}
}
