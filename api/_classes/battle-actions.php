<?php

include __DIR__.'/random-generator.php';
include __DIR__.'/../_data/battle-cries.php';

class BattleActions {
	static private $actions;
	static private $heroes;
	static private $hero;
	static private $oneHero;
	static private $actorId;
	static private $actor;
	static private $creature;
	static private $leader;
	static private $target;

	static function init($heroes) {
		self::$heroes = $heroes;
		$keys = array_keys($heroes);
		self::$hero = $keys[0];
		self::$oneHero = count($keys) == 1;
		self::$actions = array();
	}

	static function initActor($actorId, &$actor) {
		self::$actorId = $actorId;
		self::$actor = &$actor;
		self::$creature = getCreature($actor);
	}

	static function initLeader($leader) {
		self::$leader = $leader;
	}

	private static function add($props, $duration = 0, $key = null) {
		if ($key !== null) {
			$props[$key.'Key'] = RandomGenerator::generateRandomString();
		}
		$action = array('id' => self::$actorId, 'props' => $props, 'duration' => $duration);
		array_push(self::$actions, $action);
		if (!empty($props['x'])) {
			self::$actor['x'] = $props['x'];
		}
		if (!empty($props['y'])) {
			self::$actor['y'] = $props['y'];
		}
		if (!empty($props['dir'])) {
			self::$actor['dir'] = $props['dir'];
		}
	}

	static function command() {
		global $battleCries;
		extract(self::$actor);
		$leaderType = self::$leader['type'];
		$cry = RandomGenerator::pickRandom($battleCries[$type]);
		$size = getCreatureImageSize(self::$actor, $dir.'h');
		self::add(array('dir' => $dir.'h', 'say' => $cry, 'height' => $size[1]), 20, 'say');
		$act = null;
		if ($leaderType == 2) {
			$act = self::getAct();
			if ($act['type'] != 'move') {
				self::add(array('dir' => $dir));
			}
			self::add($act['data']);
			if ($act['type'] == 'move') {
				self::add(array('dir' => $dir));
			}
			return;
		}
		self::add(array('dir' => $dir));
	}

	static function act() {
		$act = self::getAct();
		if (!empty($act)) {
			self::add($act['data'], $act['duration']);
		}
	}

	static private function initTarget() {
		if (self::$oneHero) {
			self::$target = self::$hero;
		}
		self::$target = self::getNearestHero();
	}

	static private function getNearestHero() {

	}

	static private function getAct() {
		self::initTarget();
		$attack = self::getAttack();
		if (!empty($attack)) {
			return self::attack();
		}
		return self::move();
	}

	static private function move() {
		$move = self::getMove();
		if (is_array($move)) {
			self::add($move, 30);
		}
		return null;
	}

	static private function getAttack() {
		extract(self::$creature);
		if (!empty($useMagic) && RandomGenerator::getByPercent($useMagic)) {
			return self::getMagicAttack();
		}
		if ($rweapon == 'bow' || $rweapon == 'arbalest') {
			return self::getRangedAttack();
		}
		if (!empty($thweapon) && !empty($useThrow) && RandomGenerator::getByPercent($useThrow)) {
			return self::getThrowingAttack();
		}
		return self::getMeleeAttack();
	}

	static private function getMeleeAttack() {
		if (self::canMeleeAttack()) {

		}
		return null;
	}

	static private function canMeleeAttack() {
		if (empty(self::$target)) {
			return false;
		}
		$ax = self::$actor['x'];
		$ay = self::$actor['y'];
		$tx = self::$heroes[self::$target]['x'];
		$ty = self::$heroes[self::$target]['y'];
		return $tx >= $ax - 1 && $tx <= $ax + 1 && $ty >= $ay - 1 && $ty <= $ay + 1;
	}

	static private function getMove() {
		$x = self::$actor['x'];
		$y = self::$actor['y'];
		return array('x' => $x - 1, 'y' => $y - 1, 'dir' => 'l');
	}

	static function get() {
		return self::$actions;
	}
}