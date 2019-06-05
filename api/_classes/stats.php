<?php

class Stats {
	private static $data;

	static function get() {
		if (self::$data === null) {
			self::$data = Saver::get('stats');
		}
		if (!is_array(self::$data)) {
			self::$data = array();
		}
		return self::$data;
	}

	static function getStat($name, $defaultValue = null) {
		$data = self::get();
		return !is_null($data[$name]) ? $data[$name] : $defaultValue;
	}

	static function count() {
		self::$data = array(
			'detection' => 20,
			'stealth' => 10
		);
		return self::$data;
	}
}