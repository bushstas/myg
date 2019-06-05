<?php

include '../init.php';

Equip::change();
$stats = Stats::count();
Saver::save('stats', $stats);
Saver::success();
