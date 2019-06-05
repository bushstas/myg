<?php

// types
// 0 - Командовать и выжидать
// 1 - Командовать и после всех атаковать
// 2 - Командовать и одновременно атаковать

// support - поддержка во время боя
// critic - критика во время боя
// rage - добивания своих

$leadership = array(
	'skeleton' => array('chance' => 17, 'types' => array(0 => 1, 1 => 1, 2 => 1), 'support' => true, 'critic' => true, 'rage' => true)
);