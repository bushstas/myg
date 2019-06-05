<?php

class Equip {
	static function change() {
		$key = $_REQUEST['k'];
		$item = $_REQUEST['i'];

		self::takeOff($key);
	}

	static function takeOn($key, $item) {
		
	}

	static function takeOff($key) {

	}
}