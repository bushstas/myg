<?php

include __DIR__.'/../_data/monsters.php';

class Monsters {
	private static $cache = array();

	static function getByTypeAndLevel($types, $level) {
		global $monsters;
		if (!is_array($types)) {
			$types = array($types);
		}
		$list = array();
		foreach ($types as $type) {
			$listByType = array();
			$key = $type.'_'.$level;
			if (is_array($cache[$key])) {
				$list = array_merge($list, $cache[$key]);
			} elseif (is_array($monsters[$type])) {
				$cache[$key] = array();
				foreach ($monsters[$type] as $cid => $monster) {
					if ($monster['level'] == $level) {
						$monster['cid'] = $cid;
						$cache[$key][] = $monster;
						$list[] = $monster;
					}
					if ($monster['level'] > $level) {
						break;
					}
				}
			}
		}
		return $list;
	}
}
