<?php

class Module_Ajax extends Module_Abstract
{
	protected $headers = array('Content-type' => 'application/json');

	public function __construct($url) {
		parent::__construct($url);

		if (!empty($_FILES)) {
			$this->headers = array('Content-type' => 'text/html');
		}
	}

	public function send_output() {
		echo trim(json_encode($this->get_data()));
	}

	protected function get_data() {
		$method = 'do_' . rtrim($this->url[2], '?');

		$get = $this->clean_globals($_GET, array());
		$post = $this->clean_globals($_POST, array());

		if (method_exists($this, $method)) {
			return $this->$method(array_merge($post, $get));
		}

		return array('error' => Error::INCORRECT_URL, 'success' => false);
	}

	protected function clean_globals($data, $input = array(), $iteration = 0) {
		if ($iteration > 10) {
			return $input;
		}

		foreach ($data as $k => $v) {

			if (is_array($v)) {
				$input[$k] = $this->clean_globals($data[$k], array(), $iteration + 1);
			} else {
				$v = stripslashes($v);

				$v = str_replace(chr('0'),'',$v);
				$v = str_replace("\0",'',$v);
				$v = str_replace("\x00",'',$v);
				$v = str_replace('%00','',$v);
				$v = str_replace("../","&#46;&#46;/",$v);

				$input[$k] = $v;
			}
		}

		return $input;
	}

	protected function encode_password ($password) {
		return md5($password);
	}

	/* From here are realization functions */

	protected function do_upload ($get) {
		if (!empty($_FILES)) {
			$file = current(($_FILES));
			$file = $file['tmp_name'];
			$name = $file['name'];
		} else {
			$file = file_get_contents('php://input');
			$name = urldecode($get['qqfile']);
		}

		$worker = new Transform_Upload_File($file, $name);

		try {
			$data = $worker->process_file();
			$data['success'] = true;
		} catch (Error_Upload $e) {
			$data = array('error' => $e->getCode());
			$data['success'] = false;
		}

		return $data;
	}

	protected function do_delete_file ($get) {
		if (!isset($get['id']) || !is_numeric($get['id'])) {
			return array('success' => false);
		}

		$file = Database::get_full_row('file', $get['id']);
		Database::delete('file', $get['id']);
		Database::delete('strip_file', 'id_file = ?', $get['id']);
		$filename = FILES . SL . $file['md5'] . '.' . $file['ext'];
		chmod($filename, 0000);

		return array('success' => true);
	}

	protected function do_rename_file ($get) {
		if (!isset($get['id']) || !is_numeric($get['id']) || empty($get['name'])) {
			return array('success' => false);
		}

		Database::update('file', array('filename' => $get['name']), $get['id']);

		return array('success' => true);
	}

	protected function do_login ($get) {
		$password = $this->encode_password($get['password']);
		$login = preg_replace('/[^a-zа-яё_\s\d]/ui', '', $get['login']);

		if (!Database::get_count('user', 'login = ?', $login)) {
			return array('error' => 'login_not_exist', 'success' => false);
		}

		$cookie = Database::get_field('user', 'cookie',
			'login = ? and password = ?', array($login, $password));

		if (empty($cookie)) {
			return array('error' => 'password_incorrect', 'success' => false);
		}

		setcookie('user', $cookie, time() + MONTH, '/');

		return array('success' => true);
	}

	protected function do_add_strip ($get) {
		if (empty($get['title'])) {
			return array('success' => false);
		}

		$title = $get['title'];
		$text = empty($get['text']) ? '' : $get['text'];
		$date = empty($get['date']) ? '' : $get['date'];
		$pictures = empty($get['picture']) ? array() : (array) $get['picture'];
		$order = empty($get['order']) ? 0 : (int) $get['order'];

		$insert = array(
			'text' => $text,
			'title' => $title,
			'order' => $order
		);

		if (preg_match('/^\s*(\d{2})\s*\/\s*(\d{2})\s*\/\s*(\d{2})\s*$/ui', $date, $date_parts)) {
			$insert['date'] = '20' . $date_parts[1] . '-' . $date_parts[2] . '-'  . $date_parts[3];
		}

		Database::insert('strip', $insert);
		$id = Database::last_id();
		foreach ($pictures as $key => $picture) {
			Database::insert('strip_file', array(
				'id_strip' => $id,
				'id_file' => (int) $picture,
				'order' => (int) $key
			));
		}

		return array('success' => true, 'id' => $id);
	}

	protected function do_delete_strip ($get) {
		if (!isset($get['id']) || !is_numeric($get['id'])) {
			return array('success' => false);
		}

		Database::delete('strip', $get['id']);
		return array('success' => true);
	}

	protected function do_edit_strip ($get) {
		if (empty($get['title']) || !isset($get['id']) || !is_numeric($get['id'])) {
			return array('success' => false);
		}

		$id = $get['id'];
		$title = $get['title'];
		$text = empty($get['text']) ? '' : $get['text'];
		$date = empty($get['date']) ? '' : $get['date'];
		$pictures = empty($get['picture']) ? array() : (array) $get['picture'];
		$order = empty($get['order']) ? 0 : (int) $get['order'];

		$update = array(
			'text' => $text,
			'title' => $title,
			'order' => $order
		);

		if (preg_match('/^\s*(\d{2})\s*\/\s*(\d{2})\s*\/\s*(\d{2})\s*$/ui', $date, $date_parts)) {
			$update['date'] = '20' . $date_parts[1] . '-' . $date_parts[2] . '-'  . $date_parts[3];
		}

		Database::update('strip', $update, $id);
		Database::delete('strip_file', 'id_strip = ?', $id);
		foreach ($pictures as $key => $picture) {
			Database::insert('strip_file', array(
				'id_strip' => $id,
				'id_file' => (int) $picture,
				'order' => (int) $key
			));
		}

		return array('success' => true);
	}
}
