<?php

include '../init.php';
include __DIR__.'/../_classes/battle-actions.php';

Auth::init();
$battle = Saver::get('battle');

BattleActions::init($battle['heroes']);
foreach ($battle['groups'] as $groupId => $ids) {
	foreach ($ids as $id) {
		$actor = &$battle['enemies'][$id];
		BattleActions::initActor($id, $actor);
		if ($actor['leader'] && $turn === 0) {
			BattleActions::initLeader($battle['leaders'][$groupId]);
			BattleActions::command();
			continue;
		}
		BattleActions::act();
	}
}

Saver::save('battle', $battle);
Saver::success(BattleActions::get());
