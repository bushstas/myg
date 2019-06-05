<?php

class Saver {
	static function get($name) {
		$file = Auth::getUserAsset($name);		
		if (file_exists($file)) {
			include $file;
			return $$name;
		}
		return array();
	}

	static function save($name, $data) {
		$content = stringify($data, $name);
		if (!is_dir('../_users')) {
			mkdir('../_users');
		}
		$folder = Auth::getUserAsset();
		if (!is_dir($folder)) {
			mkdir($folder);
		}
		$file = $folder.'/'.$name.'.php';
		file_put_contents($file, $content);
	}

	static function success($data = null) {
		$response = array('success' => true);
		if (is_array($data)) {
			$response['data'] = $data;
		}
		die(json_encode($response));
	}

	static function failure($error = null) {
		$response = array('success' => false);
		if (!empty($error)) {
			$response['error'] = $error;
		}
		die(json_encode($response));
	}
}