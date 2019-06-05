<?php

include __DIR__.'/monsters.php';
include __DIR__.'/random-generator.php';
include __DIR__.'/../_data/dmgtypes.php';
include __DIR__.'/../_data/leadership.php';

class BattleCreator {
	static private $dir;
	static private $mapSize;
	static private $currentGroup;	
	static private $coords;
	static private $groupLocations;
	static private $maxDetection;
	static private $minStealth;
	static private $heroTurn;
	static private $leaders = array();
	static private $groups = array();

	static function create($options) {
		extract($options);		
		self::$maxDetection = 0;
		self::$minStealth = 999999;
		$groups = self::createEnemies($area);

		self::$coords = array();
		self::$groupLocations = array();
		self::initMapSize();
		self::locateEnemies($groups);
		$heroes = array(RandomGenerator::generateRandomString() => self::createHero($options));

		$enemies = array();
		foreach ($groups as $group) {
			$enemies = array_merge($enemies, $group);
		}
		$data = array(
			'heroes' => $heroes,
			'enemies' => $enemies,
			'groups' => self::$groups,
			'size' => self::$mapSize,
			'turn' => 0,
			'heroTurn' => self::$heroTurn
		);
		if (!empty(self::$leaders)) {
			$data['leaders'] = self::$leaders;
		}
		return $data;
	}

	private static function locateEnemies(&$groups) {
		$groupKeys = array_keys(self::$groups);
		foreach ($groups as $index => &$group) {
			self::$currentGroup = $groupKeys[$index];
			foreach ($group as $k => &$enemy) {
				$coords = self::generateEnemyCoords();
				$enemy['dir'] = $coords['dir'];
				$enemy['x'] = $coords['x'];
				$enemy['y'] = $coords['y'];
				$enemy['height'] = self::getImageHeight($enemy['type'], $enemy['cid'], $coords['dir']);
			}
		}
	}

	private static function initMapSize() {
		$heroDetection = Stats::getStat('detection');
		$heroStealth = Stats::getStat('stealth');

		$detection = $heroDetection - self::$minStealth;
		$enemyDetection = self::$maxDetection - $heroStealth;
		self::$heroTurn = $detection >= $enemyDetection;

		self::$dir = RandomGenerator::pickRandom(array(
			'top', 'right', 'bottom', 'left'
		));
		
		if (self::$dir === 'top' || self::$dir === 'bottom') {
			self::$mapSize = array(11, $detection + 4);
		} else {
			self::$mapSize = array($detection + 4, 11);
		}
	}

	static function createHero($options) {
		global $dmgtypes;
		
		$dir = self::$dir;
		$size = self::$mapSize;

		if ($dir == 'top' || $dir == 'bottom') {
			$x = ceil($size[0] / 2);
		} elseif ($dir == 'left') {
			$x = 1; 
		} else {
			$x = $size[0]; 
		}
		if ($dir == 'left' || $dir == 'right') {
			$y = ceil($size[1] / 2);
		} elseif ($dir == 'top') {
			$y = 1; 
		} else {
			$y = $size[1]; 
		}
		$type = 'human';
		$cid = 'ahdfgswkfh';
		$dir = 'l';
		return array(
			'type' => $type,
			'cid' => $cid,
			'dir' => $dir,
			'x' => $x,
			'y' => $y,
			'speed' => 2,
			'wr' => 'sword',
			'dmgtype' => isset($dmgtypes[$type]) ? $dmgtypes[$type] : 'blood',
			'height' => self::getImageHeight($type, $cid, $dir)
		);
	}

	static function createEnemies($options) {
		extract($options);
		$groups = array();
		$groupsCount = 1;
		if (!empty($maxGroupsCounts) && is_array($maxGroupsCounts)) {
			$groupsCount = RandomGenerator::getByChance($maxGroupsCounts);
		} 
		for ($i = 0; $i < $groupsCount; $i++) {
			$type = RandomGenerator::getByChance($monsters);
			$groups[] = self::createEnemyGroup($type, $options);
		}
		return $groups;
	}

	static function createEnemyGroup($type, $options) {
		global $leadership;
		extract($options);
		self::$currentGroup = RandomGenerator::generateRandomString();
		self::$groups[self::$currentGroup] = array();
		$enemyCount = RandomGenerator::generate($minMonsters, $maxMonsters);
		$withLeader = RandomGenerator::getByPercent($leadership[$type]['chance']);
		$enemies = array();
		for ($i = 0; $i < $enemyCount; $i++) {
			$id = RandomGenerator::generateRandomString();
			$isLeader = $withLeader && $i == 0;
			$enemy = self::createEnemy($type, $options, $isLeader, $id);
			$enemies[$id] = self::toProperEnemy($type, $enemy);
			array_push(self::$groups[self::$currentGroup], $id);
			if ($isLeader) {
				$enemies[$id]['leader'] = true;
				self::$leaders[self::$currentGroup] = array(
					'id' => $id,
					'type' => RandomGenerator::getByChance($leadership[$type]['types'])
				);
			}
			if (is_int($enemy['detection']) && self::$maxDetection < $enemy['detection']) {
				self::$maxDetection = $enemy['detection'];
			}
			$stealth = is_int($enemy['stealth']) ? $enemy['stealth'] : 0;
			if (self::$minStealth > $stealth) {
				self::$minStealth = $stealth;
			}
		}
		return $enemies;
	}

	static function createEnemy($type, $options, $isLeader, $id) {
		extract($options);
		$level = RandomGenerator::generate($minMonsterLevel, $maxMonsterLevel);
		if ($isLeader) {
			$level += RandomGenerator::getByChance(array(1 => 4, 2 => 8, 3 => 2, 4 => 1));
		}
		$monsters = Monsters::getByTypeAndLevel($type, $level);
		return RandomGenerator::pickRandom($monsters);
	}

	static function toProperEnemy($type, $enemy) {
		global $dmgtypes;
		$properEnemy = array(
			'type' => $type,
			'cid' => $enemy['cid'],
			'speed' => 2,
			'wr' => $enemy['rweapon'],
			'dmgtype' => isset($dmgtypes[$type]) ? $dmgtypes[$type] : 'blood',
			'hp' => RandomGenerator::pickRandom($enemy['hp'])
		);
		if ($enemy['lweapon'] !== null) {
			$properEnemy['wl'] = $enemy['lweapon'];
		}
		return $properEnemy;
	}

	static function isCellOccupied($x, $y) {
		return self::$coords[$x.'_'.$y] === true;
	}

	static function generateEnemyPosition() {
		if (self::$groupLocations[self::$currentGroup] === null) {
			if (self::$dir == 'top') {
				$locs = array('bottom_left', 'bottom_center', 'bottom_right');
			} elseif (self::$dir == 'bottom') {
				$locs = array('top_left', 'top_center', 'top_right');
			} elseif (self::$dir == 'right') {
				$locs = array('top_left', 'center_left', 'bottom_left');
			} else {
				$locs = array('top_right', 'center_right', 'bottom_right');
			}
			$loc = RandomGenerator::pickRandom($locs);
			self::$groupLocations[self::$currentGroup] = $loc;
		} else {
			$loc = self::$groupLocations[self::$currentGroup];
		}
		$parts = explode('_', $loc);
		switch ($parts[0]) {
			case 'top':
				$y = RandomGenerator::generate(1, floor(self::$mapSize[1] / 2));
			break;
			case 'center':
				$shift = RandomGenerator::pickRandom(array(1, 2));
				$start = $shift == 1 ? floor(self::$mapSize[1] / 4) : ceil(self::$mapSize[1] / 4);
				$y = RandomGenerator::generate($start, floor(self::$mapSize[1] / 2) - 1);
			break;
			case 'bottom':
				$y = RandomGenerator::generate(ceil(self::$mapSize[0] / 2), self::$mapSize[0]);
			break;
		}
		switch ($parts[1]) {
			case 'left':
				$x = RandomGenerator::generate(1, floor(self::$mapSize[1] / 2));
			break;
			case 'center':
				$shift = RandomGenerator::pickRandom(array(1, 2));
				$start = $shift == 1 ? floor(self::$mapSize[0] / 4) : ceil(self::$mapSize[0] / 4);
				$x = RandomGenerator::generate($start, floor(self::$mapSize[0] / 2) - 1);
			break;
			case 'right':
				$x = RandomGenerator::generate(ceil(self::$mapSize[0] / 2), self::$mapSize[0]);
			break;
		}

		return array('x' => $x, 'y' => $y);
	}

	static function generateEnemyCoords() {
		$coords = self::generateEnemyPosition();
		$x = $coords['x'];
		$y = $coords['y'];
		while (self::isCellOccupied($x, $y)) {
			$coords = self::generateEnemyPosition();
			$x = $coords['x'];
			$y = $coords['y'];
		}
		self::$coords[$x.'_'.$y] = true;
		return array(
			'x' => $x,
			'y' => $y,
			'dir' => RandomGenerator::pickRandom(array('l', 'r'))
		);
	}

	static function getImageHeight($type, $image, $dir) {
		$filename = __DIR__.'../../../assets/images/creatures/'.$type.'/'.$image.'_'.$dir.'.png';
		$size = getimagesize($filename);
		return $size[1];
	}
}