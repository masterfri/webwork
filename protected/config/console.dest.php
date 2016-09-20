<?php

return array (
	'basePath' => dirname(__FILE__) . '/..',
	'preload' => array('log'),
	'components' => array(
		'db' => array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=pm',
			'username' => 'user',
			'password' => 'password',
			'tablePrefix' => '',
			'charset' => 'utf8',
		),
		'cache' => array(
			'class' => 'CFileCache',
		),
        'format' => array(
			'class' => 'Formatter',
			'booleanFormat' => array('Нет', 'Да'),
			'datetimeFormat' => 'd/m/Y H:i',
			'dateFormat' => 'd/m/Y',
			'timeFormat' => 'H:i',
			'numberFormat' => array(
				'decimals' => 2,
				'decimalSeparator' => '.',
				'thousandSeparator' => '',
			),
        ),
	),
	'modules' => array(
		'core',
	),
	'import' => array(
		'application.components.*',
		'application.models.*',
		'application.extensions.*',
	),
	'commandMap' => array(
		'invoice' => 'core.commands.InvoiceCommand',
		'translate' => 'core.commands.TranslateCommand',
	),
	'name' => 'Webwork',
	'language' => 'ru',
);
