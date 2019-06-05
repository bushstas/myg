<?php

class Auth {
	private static $userId;

	static function init() {
		self::$userId = 'skfjrfhry3wgfdy43r7d';
	}

	static function getUserId() {
		return self::$userId;
	}

	static function getUserAsset($name = null) {
		if ($name === null) {
			return __DIR__.'/../_users/'.self::$userId;
		}
		return __DIR__.'/../_users/'.self::$userId.'/'.$name.'.php';
	}
}