<?php

class RandomGenerator {
	static function generate($from, $to) {
		return rand($from, $to);
	}

	static function getByChance($chances) {
		$values = array();
		foreach ($chances as $key => $value) {
			for ($i = 0; $i < $value; $i++) {
				$values[] = $key;
			}
		}
		shuffle($values);
		$random = rand(0, count($values) - 1);
		return $values[$random];
	}

	static function getByPercent($percentage) {
		$percentage2 = 100 - $percentage;
		$p = $percentage / 10;
		if ($p == floor($p)) {
			$percentage = $p;
			$percentage2 /= 10;
		}
		$chances = array('y' => $percentage, 'n' => $percentage2);
	    return self::getByChance($chances) == 'y';
	}

	static function pickRandom($items) {
		if (!is_array($items)) {
			return $items;
		}
		$index = rand(0, count($items) - 1);
		shuffle($items);
		return $items[$index];
	}

	static function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
}
