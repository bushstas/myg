<?php

include __DIR__.'/../_classes/battle-actions.php';

$userId = 'skfjrfhry3wgfdy43r7d';
$file = __DIR__.'/../_users/'.$userId.'/battle.php';
$battle = json_decode(file_get_contents($file), true);
extract($battle);


BattleActions::init($heroes);
foreach ($groups as $groupId => $ids) {
	foreach ($ids as $id) {
		$actor = $enemies[$id];
		BattleActions::initActor($id, $actor);
		if ($actor['leader'] && $turn === 0) {
			BattleActions::initLeader($leaders[$groupId]);
			BattleActions::command();
			continue;
		}
		BattleActions::act();
	}
}

// array(
// 	array('id' => 'a', 'props' => array('x' => 7, 'y' => 2), 'duration' => 13),
// 	array('id' => 'b', 'props' => array('dir' => 'rh', 'sayKey' => 1, 'say' => 'Kill him!!!'), 'duration' => 20),
// 	array('id' => 'b', 'props' => array('dir' => 'r')),
// 	array('id' => 'd', 'props' => array('hitKey' => 1, 'hit' => array())),
// 	array('id' => 'hero', 'props' => array('dmgKey' => 1, 'dmg' => 10)),
// 	array('id' => 'a', 'props' => array('dmgKey' => 2, 'dmg' => 10), 'duration' => 10),
// )

die(json_encode(array('data' => BattleActions::get())));

