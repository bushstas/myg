<?php

include '../init.php';

Auth::init();
$battle = Saver::get('battle');

$data = array('data' => array(
	'characters' => array_merge($battle['heroes'], $battle['enemies']),
	'size' => $battle['size'],
	'dictionary' => array(
		'endturn' => 'Пропустить ход'
	)
));

die(json_encode($data));

