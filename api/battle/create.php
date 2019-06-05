<?php

include '../init.php';
include '../_classes/create-battle.php';
include '../_data/areas.php';

$userId = 'skfjrfhry3wgfdy43r7d';

$battle = BattleCreator::create(array(
	'area' => $areas['efdklhjufk']
));

$content = stringify($battle, 'battle');
$folder = __DIR__.'/../_users/'.$userId;
if (!is_dir($folder)) {
	mkdir($folder);
}
$file = $folder.'/battle.php';
file_put_contents($file, $content);
