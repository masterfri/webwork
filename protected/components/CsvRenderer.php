<?php

class CsvRenderer extends CWidget
{
	public $showHeads = false;
	public $attributes;
	public $provider;
	public $delimiter = ',';
	public $enclosure = '"';
	public $filename = 'export.csv';
	
	protected $_stream;
	protected $_formatter;
	
	public function run($returnString = false)
	{
		$this->prepareAttributes();
		$this->openStream();
		
		if ($this->showHeads) {
			$this->renderHeads();
		}
		
		foreach ($this->provider->getData() as $item) {
			$this->renderItem($item);
		}
		
		$res = $this->getStreamContents();
		$this->closeStream();
        if ($returnString) {
            return $res;
        } else {
			header("Pragma: no-cache");
			header("Expires: 0");
			header("Content-type: text/csv");
			if (false !== $this->filename) {
				header("Content-Disposition: attachment; filename=" . $this->filename);
			}
            echo $res;
            exit;
        }
	}
	
	protected function renderItem($item)
	{
		$formatter = $this->getFormatter();
		$vals = array();
		foreach ($this->attributes as $attribute) {
			if (isset($attribute['value'])) {
				$value = $this->evaluateExpression($attribute['value'], array (
					'data' => $item,
				));
			} else {
				$value = CHtml::value($item, $attribute['name']);
			}
			
			$vals[] = $formatter->format($value, $attribute['type']); 
		}
		$this->renderCsvRow($vals);	
	}
	
	protected function renderHeads()
	{
		$model = $this->provider->model;
		$vals = array();
		foreach ($this->attributes as $attribute) {
			$vals[] = isset($attribute['label']) ? $attribute['label'] : $model->getAttributeLabel($attribute['name']);
		}
		$this->renderCsvRow($vals);
	}
	
	protected function renderCsvRow($vals)
	{
		if (!$this->_stream) {
			throw new CException('Trying to write to non-opened stream');
		}
		fputcsv($this->_stream, $vals, $this->delimiter, $this->enclosure);
	}
	
	protected function openStream()
	{
		$this->closeStream();
		$this->_stream = fopen("php://temp/maxmemory:67108864", 'r+'); // Max 64 Mb
	}
	
	protected function closeStream()
	{
		if ($this->_stream) {
			fclose($this->_stream);
			$this->_stream = null;
		}
	}
	
	protected function getStreamContents()
	{
		if (!$this->_stream) {
			throw new CException('Trying to read from non-opened stream');
		}
		rewind($this->_stream);
		return stream_get_contents($this->_stream);
	}
	
	protected function prepareAttributes()
	{
		foreach ($this->attributes as $n => $attribute) {
			if(is_string($attribute)) {
				if(!preg_match('/^([\w\.]+)(:(\w*))?(:(.*))?$/',$attribute, $matches)) {
					throw new CException(Yii::t('zii','The attribute must be specified in the format of "Name:Type:Label", where "Type" and "Label" are optional.'));
				}
				$attribute = array( 
					'name' => $matches[1], 
					'type' => isset($matches[3]) ? $matches[3] : 'text', 
				);
				if(isset($matches[5])) {
					$attribute['label'] = $matches[5]; 
				}
				$this->attributes[$n] = $attribute;
			}
			if (! isset($attribute['type'])) {
				$this->attributes[$n]['type'] = 'raw';
			}
		}
	}
	
	public function getFormatter()
	{ 
		if($this->_formatter === null) {
			$this->_formatter = Yii::app()->format; 
		}
		return $this->_formatter; 
	}
	
	public function setFormatter($value) 
	{ 
		$this->_formatter = $value; 
	}
}
