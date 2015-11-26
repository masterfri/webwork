<?php

class CodeforgeComponent extends CApplicationComponent
{
	public $cf_dir;
	
	protected $layer;
	
	public function setup($workdir=null)
	{
		if (defined('CF_SCRIPT')) {
			throw new CException('Second call of setup() is not allowed');
		}
		
		if (null === $workdir) {
			$workdir = Yii::app()->getRuntimePath();
		}
		
		define('CF_SCRIPT', 'cf');
		define('CF_PROGRAMM', 'CodeForge');
		define('CF_VERSION', '2.0-dev');
		define('CF_THISDIR', $this->cf_dir);
		define('CF_WORKDIR', $workdir);
		define('CF_LIB_DIR', CF_THISDIR . '/lib');
		
		require_once CF_LIB_DIR . '/WebLayer.php';
		
		$this->layer = new Codeforge\WebLayer();
		$this->layer->setSchemesDir(array(
			CF_THISDIR . '/schemes',
		));
		$this->layer->setExtensionsDir(array(
			CF_THISDIR . '/extensions',
		));
	}
	
	public function getCustomTypes()
	{
		return array_keys($this->layer->getCustomTypes());
	}
	
	public function getStandardTypes()
	{
		return array(
			'int',
			'decimal',
			'char',
			'text',
			'bool',
			'option',
			'enum',
		);
	}
}
