<?php

class TranslateCommand extends CConsoleCommand
{
	const RE = '/Yii::t[(]\s*[\'"]([a-z0-9._]+)[\'"]\s*,\s*([\'"])(.*)(?<!\\\\)\2/iU';
	protected $extensions = array('php');
	protected $output = '.';
	protected $language = 'ru';
	
	protected $translations;
	protected $changes = array();
	
	public function actionScan($dir, $out='.', $lng='ru')
	{
		$path = realpath($out);
		if (!$path) {
			echo "Directory not exists: $out\n";
			exit;
		}
		$this->output = rtrim($path, '/') . '/';
		$this->language = $lng;
		$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
		foreach ($files as $file) {
			if (!$file->isDir() && in_array($file->getExtension(), $this->extensions)) {
				$this->scanFile($file->getPathname());
			}
		}
		foreach ($this->translations as $category => $strings) {
			if (isset($this->changes[$category])) {
				$this->saveTranslations($category, $strings);
			}
		}
	}
	
	protected function scanFile($file)
	{
		$f = fopen($file, 'r');
		if (!$f) {
			return false;
		}
		while (!feof($f)) {
			$line = fgets($f);
			$translator = $this->matchTranslator($line);
			if ($translator) {
				list($category, $string) = $translator;
				if (!isset($this->translations[$category])) {
					$this->translations[$category] = $this->loadTranslations($category);
				}
				if (!isset($this->translations[$category][$string])) {
					$this->translations[$category][$string] = '';
					$this->changes[$category] = true;
				}
			}
		}
		fclose($f);
	}
	
	protected function matchTranslator($line)
	{
		if (preg_match(self::RE, $line, $matches)) {
			return array($matches[1], $matches[3]);
		}
		return null;
	}
	
	protected function loadTranslations($category)
	{
		try {
			$lng = $this->language;
			if (($pos = strpos($category, '.')) !== false) {
				$extensionClass = substr($category, 0, $pos);
				$extensionCategory = substr($category, $pos + 1);
				$path = Yii::getPathOfAlias("$extensionClass.messages.$lng") . '/' . $extensionCategory . '.php';
			} else {
				$path = Yii::getPathOfAlias("application.messages.$lng") . '/' . $category . '.php';
			}
			if (is_file($path)) {
				return require($path);
			}
		} catch (Exception $e) {
		}
		return array();
	}
	
	protected function saveTranslations($category, $strings)
	{
		ksort($strings);
		$fullpath = $this->output . implode('/', explode('.', $category)) . '.php';
		$dir = dirname($fullpath);
		if (!is_dir($dir)) {
			if (!@mkdir($dir, 0755, true)) {
				echo "Can not create directory $dir\n";
				exit;
			}
		}
		$content = "<?php\n\nreturn array(\n";
		foreach ($strings as $src => $trans) {
			$content .= "\t'" . addslashes($src) . "' => ";
			if (empty($trans)) {
				$content .= "'" . addslashes($src) . "', // TODO: translate\n";
			} else {
				$content .= "'" . addslashes($trans) . "',\n";
			}
		}
		$content .= ");";
		if (!@file_put_contents($fullpath, $content)) {
			echo "Can not write file $fullpath\n";
			exit;
		}
		echo "Translations for $category have been updated\n;";
	}
}
