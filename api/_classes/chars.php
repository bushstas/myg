<?php

class Chars {
	private static $data;

	static function get() {
		if (self::$data === null) {
			self::$data = Saver::get('chars');
		}
		if (!is_array(self::$data)) {
			self::$data = array();
		}
		return self::$data;
	}
}