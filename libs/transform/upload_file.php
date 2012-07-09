<?php

class Transform_Upload_File extends Transform_Upload_Abstract_Image
{
	protected function get_max_size() {
		return 50*1024*1024;
	}

	protected function process() {
		$md5 = md5(microtime(true));
		$ext = pathinfo($this->name, PATHINFO_EXTENSION);
		$name = FILES. SL . $md5 . '.' . $ext;

		chmod($this->file, 0755);
		if (!move_uploaded_file($this->file, $name)) {
			file_put_contents($name, file_get_contents($this->file));
		}

		Database::insert('file', array(
			'md5' => $md5,
			'ext' => $ext,
			'filename' => $this->name,
		));
	}
}
