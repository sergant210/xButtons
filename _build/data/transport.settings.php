<?php

$settings = array();

$tmp = array(
	'files_path' => array(
		'xtype' => 'textfield',
		'value' => '{core_path}components/xbuttons/elements/',
		'area' => 'xbuttons_main',
	),
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'xbuttons_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
