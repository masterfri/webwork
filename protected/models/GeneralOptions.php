<?php

class GeneralOptions extends OptionRecord 
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public static function instance($class=__CLASS__)
	{
		return parent::instance($class);
	}
	
	public function attributeLabels()
	{
		return array(
			'app_domain' => Yii::t('core.generalOptions', 'Application Domain'),
			'complexity_rate' => Yii::t('core.generalOptions', 'Complexity Rate'),
			'estimate_error_rate' => Yii::t('core.generalOptions', 'Estimate Error Rate'),
			'httpsh_host' => Yii::t('core.generalOptions', 'Host'),
			'httpsh_port' => Yii::t('core.generalOptions', 'Port'),
			'httpsh_login' => Yii::t('core.generalOptions', 'Login'),
			'httpsh_password' => Yii::t('core.generalOptions', 'Password'),
		);
	}
	
	public function rules()
	{
		return CMap::mergeArray(parent::rules(), array(
			array('	app_domain,
					complexity_rate,
					estimate_error_rate,
					httpsh_host,
					httpsh_port,
					httpsh_login,
					httpsh_password', 
					'required'),
			array(' app_domain',
					'filter', 'filter' => 'trim'),
			array(' complexity_rate,
					estimate_error_rate,
					httpsh_port',
					'numerical'),
		));
	}
	
	protected function metaFields()
	{
		return array(
			'app_domain',
			'complexity_rate',
			'estimate_error_rate',
			'httpsh_host',
			'httpsh_port',
			'httpsh_login',
			'httpsh_password'
		);
	}
}
