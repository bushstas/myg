<?php

include_once __DIR__.'/../_data/monsters.php';

function getCreatureImageSize($data, $dir) {
	$path = __DIR__.'/../../assets/images/creatures/'.$data['type'].'/'.$data['img'].'_'.$dir.'.png';
	return getimagesize($path);
}

function getCreature($data) {
	global $monsters;
	return $monsters[$data['type']][$data['cid']];
}

function stringifyArray($arr) {
	$items = array();
	foreach ($arr as $k => $v) {
		if (is_int($k)) {
			$items[] = stringifyVar($v);
		} else {
			$items[] = '\''.$k.'\'=>'.stringifyVar($v);
		}
	}
	return 'array('.implode(',', $items).')';
}

function stringifyVar($var) {
	if (is_array($var)) {
		return stringifyArray($var);
	} else if (is_string($var)) {
		return '\''.$var.'\'';
	} else if (is_numeric($var)) {
		return $var;
	} else if (is_bool($var)) {
		return $var === true ? 'true' : 'false';
	}
	return '\'\'';
}

function stringify($var, $name) {
	return '<?php $'.$name.'='.stringifyVar($var).'; ?>';
}
