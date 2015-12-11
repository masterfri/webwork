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
		require_once CF_LIB_DIR . '/Parser.php';
		require_once CF_LIB_DIR . '/Attribute.php';
		require_once CF_LIB_DIR . '/FileHelper.php';
		
		$this->layer = new Codeforge\WebLayer();
		$this->layer->setSchemesDir(array(
			CF_THISDIR . '/schemes',
		));
		$this->layer->setExtensionsDir(array(
			CF_THISDIR . '/extensions',
		));
		$this->layer->setCacheDir($this->prepareDir(CF_WORKDIR . '/cache'));
		$this->layer->setPartialDir($this->prepareDir(CF_WORKDIR . '/partial'));
		$this->layer->setStaticPartialDir($this->prepareDir(CF_WORKDIR . '/static-partial'));
	}
	
	public function getCustomTypes()
	{
		return array_keys($this->layer->getCustomTypes());
	}
	
	public function getCompileSchemes()
	{
		$list = $this->layer->getSchemesList();
		return $list['_compile'];
	}
	
	public function getBuildSchemes()
	{
		$list = $this->layer->getSchemesList();
		return $list['_build'];
	}
	
	public function getPackages()
	{
		return $this->layer->getPkgList();
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
	
	public function build($entities, $schemes, $options=array())
	{
		ob_start();
		try {
			$models = array();
			foreach ($entities as $entity) {
				$parser = new Codeforge\Parser();
				$parser->parseText($entity->plain_source);
				foreach ($parser->getModels() as $model) {
					$models[] = $model;
				}
			}
			$compile_dir = $this->prepareDir(CF_WORKDIR . '/compiled');
			$this->layer->setEnv($options);
			$this->layer->setModels($models);
			$this->layer->compile($compile_dir);
			if (count($schemes)) {
				$this->layer->setMode('_build');
				$this->layer->build($schemes, $compile_dir);
			}
		} catch (Exception $e) {
			ob_clean();
			return $e->getMessage();
		}
		return ob_get_clean();
	}
	
	public function deployPackage($name)
	{
		$list = $this->getPackages();
		if (!isset($list[$name])) {
			throw new CException("Package $name can not be found");
		}
		Codeforge\FileHelper::copyContents($list[$name]['dir'], CF_WORKDIR);
	}
	
	protected function prepareDir($dir)
	{
		if (!is_dir($dir)) {
			if (!@mkdir($dir, 0700, true)) {
				throw new CException('Can not create directory ' . $dir);
			}
		} elseif (!is_writable($dir)) {
			throw new CException('Directory ' . $dir . ' is not writable');
		}
		return $dir;
	}
}
