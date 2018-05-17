<?php

return array (
	'preload' => array('log'),
	'components' => array(
		'urlManager' => array(
			'urlFormat' => 'path',
			'appendParams' => false,
			'showScriptName' => false,
			'rules' => array(
				'/' => '/core/default/index',
				'/ww/dashboard/<action:\w+>' => '/core/default/<action>',
				'/ww/login' => '/core/default/login',
				'/ww/logout' => '/core/default/logout',
				'/ww/me' => '/core/user/profile',
				'/ww/me/update' => '/core/user/updateProfile',
				'/ww/<controller:\w+>/<id:\d+>' => 'core/<controller>/view',
				'/ww/<controller:\w+>/<id:\d+>/<action:\w+>' => 'core/<controller>/<action>',
				'/ww/project/<project:\d+>/<controller:\w+>/<action:\w+>' => 'core/<controller>/<action>',
				'/ww/<controller:\w+>/<action:\w+>' => 'core/<controller>/<action>',
				'/ww/<controller:\w+>' => 'core/<controller>/index',
			),
		),
		'authManager' => array(
			'class' => 'PhpAuthManager',
			'defaultRoles' => array('guest'),
		),
		'user' => array(
			'class' => 'WebUser',
			'loginUrl' => array('/core/login'),
			'allowAutoLogin' => true,
		),
		'db' => array(
			'class' => 'CDbConnection',
			'connectionString' => 'mysql:host=localhost;dbname=__DBNAME__',
			'username' => '__DBUSER__',
			'password' => '__DBPASS__',
			'tablePrefix' => '',
			'charset' => 'utf8',
			'enableProfiling'=>true,
			'enableParamLogging'=>true,
		),
		'cache' => array(
			'class' => 'CFileCache',
		),
		'errorHandler'=>array(
			'errorAction'=>'core/default/error',
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
		'log' => array(
			'class' => 'CLogRouter',
			'routes' => array(
				array(
					'class' => 'CFileLogRoute',
					'levels' => 'error, warning',
				),
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
	'layout' => 'default',
	'name' => 'Webwork',
	'language' => 'ru',
	'homeUrl' => '/',
);
