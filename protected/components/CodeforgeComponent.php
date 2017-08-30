<?php

class CodeforgeComponent extends CApplicationComponent
{
	const RESOLVE_SKIP = 1;
	const RESOLVE_IGNORE = 2;
	const RESOLVE_REPLACE = 3;
	
	const PROJECT_DIR_NAME = 'codeforge';
	
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
		
		$custom_dir = Yii::getPathOfAlias('application.cf-custom');
		
		require_once CF_LIB_DIR . '/WebLayer.php';
		require_once CF_LIB_DIR . '/Parser.php';
		require_once CF_LIB_DIR . '/Attribute.php';
		require_once CF_LIB_DIR . '/FileHelper.php';
		require_once CF_LIB_DIR . '/EasyConfig.php';
		
		$this->layer = new Codeforge\WebLayer();
		$this->layer->setSchemesDir(array(
			CF_THISDIR . '/schemes',
			$custom_dir . '/schemes',
		));
		$this->layer->setExtensionsDir(array(
			CF_THISDIR . '/extensions',
			$custom_dir . '/extensions',
		));
		$this->prepareDir(CF_WORKDIR . '/' . self::PROJECT_DIR_NAME);
		$this->layer->setCacheDir($this->prepareDir(CF_WORKDIR . '/' . self::PROJECT_DIR_NAME . '/cache'));
		$this->layer->setPartialDir($this->prepareDir(CF_WORKDIR . '/' . self::PROJECT_DIR_NAME . '/partial'));
		$this->layer->setStaticPartialDir($this->prepareDir(CF_WORKDIR . '/' . self::PROJECT_DIR_NAME . '/static-partial'));
	}
	
	public function importLib($lib)
	{
		require_once($this->cf_dir . '/lib/' . $lib . '.php');
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
					$model->addComment(sprintf('@timeCreated %d', strtotime($entity->time_created)));
					$models[] = $model;
				}
			}
			$compile_dir = $this->prepareDir(CF_WORKDIR . '/' . self::PROJECT_DIR_NAME . '/compiled');
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
		Codeforge\FileHelper::copyContents($list[$name]['dir'], CF_WORKDIR . '/' . self::PROJECT_DIR_NAME);
	}
	
	protected function prepareDir($dir)
	{
		if (!is_dir($dir)) {
			if (!@mkdir($dir, 0755, true)) {
				throw new CException('Can not create directory ' . $dir);
			}
		} elseif (!is_writable($dir)) {
			throw new CException('Directory ' . $dir . ' is not writable');
		}
		return $dir;
	}
	
	public function updateChecksum(&$data)
	{
		$checksumFile = CF_WORKDIR . '/' . self::PROJECT_DIR_NAME . '/checksum.list';
		$list = new Codeforge\EasyConfig($data);
		$list->writeToFile($checksumFile);
	}
	
	public function updateIgnoreList(&$files, $replace=false)
	{
		$ignoreFile = CF_WORKDIR . '/' . self::PROJECT_DIR_NAME . '/ignore.list';
		if (!$replace && is_file($ignoreFile)) {
			$list = new Codeforge\EasyConfig();
			$list->readFile($ignoreFile);
			$ignorelist = $list->getData();
		} else {
			$ignorelist = array();
		}
		foreach ($files as $file) {
			if (!in_array($file, $ignorelist)) {
				$ignorelist[] = $file;
			}
		}
		$list = new Codeforge\EasyConfig($ignorelist);
		$list->writeToFile($ignoreFile);
	}
	
	public function cleanup($compiledOnly=true)
	{
		if ($compiledOnly) {
			$dir = CF_WORKDIR . '/' . self::PROJECT_DIR_NAME . '/compiled';
			if (is_dir($dir)) {
				Codeforge\FileHelper::cleanup($dir, true, false);
			}
			$dir = CF_WORKDIR . '/' . self::PROJECT_DIR_NAME . '/partial';
			if (is_dir($dir)) {
				Codeforge\FileHelper::cleanup($dir, true, false);
			}
		} else {
			$dir = CF_WORKDIR . '/' . self::PROJECT_DIR_NAME;
			if (is_dir($dir)) {
				Codeforge\FileHelper::cleanup($dir, true, false);
			}
		}
	}
	
	public function buildGraph($entities)
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
			$compile_dir = $this->prepareDir(CF_WORKDIR . '/' . self::PROJECT_DIR_NAME . '/compiled');
			$this->layer->setModels($models);
			$this->layer->setMode('_build');
			$this->layer->build('debug', $compile_dir);
		} catch (Exception $e) {
			ob_clean();
			return '[]; // Error: ' . $e->getMessage();
		}
		return ob_get_clean();
	}
	
	public function getEntityReferences($entities, $to=null)
	{
		$result = array();
		foreach ($entities as $entity) {
			try {
				$parser = new Codeforge\Parser();
				$parser->parseText($entity->plain_source);
				foreach ($parser->getModels() as $model) {
					foreach ($model->getAttributes() as $attribute) {
						if ($attribute->getIsCustomType() && ($to === null || $to === $attribute->getCustomType())) {
							$result[$attribute->getCustomType()][$model->getName()][$attribute->getName()] = $attribute->getIsCollection();
						}
					}
				}
			} catch (Exception $e) {}
		}
		return $result;
	}
	
	public function parseEntity($entity)
	{
		$parser = new Codeforge\Parser();
		$parser->parseText($entity->plain_source);
		return $parser->getModels();
	}
}
