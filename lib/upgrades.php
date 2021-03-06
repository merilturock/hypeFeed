<?php

// Setup MySQL databases
run_sql_script(dirname(dirname(__FILE__)) . '/install/mysql.sql');

// Register upgrade scripts
$path = 'admin/upgrades/feed/populate';
$upgrade = new ElggUpgrade();
$upgrade = $upgrade->getUpgradeFromPath($path);

if (!$upgrade instanceof ElggUpgrade) {

	$dbprefix = elgg_get_config('dbprefix');
	$count = elgg_get_river([
		'count' => true,
		'wheres' => [
			"NOT EXISTS (SELECT 1 FROM {$dbprefix}feeds
				WHERE id = rv.id)",
		],
	]);

	if ($count) {
		$upgrade = new ElggUpgrade();
		$upgrade->setPath($path);
		$upgrade->title = elgg_echo('admin:upgrades:feed:populate');
		$upgrade->description = elgg_echo('admin:upgrades:feed:populate:description');
		$upgrade->is_completed = 0;
		$upgrade->save();
	}
}