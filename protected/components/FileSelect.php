<?php

class FileSelect extends CInputWidget
{
	public $container = 'span';
	public $containerCssClass = 'file-select-container';
	public $itemCssClass = 'file-select-item';
	public $multiple = false;
	public $buttonText = 'Select file';
	public $buttonCssClass = 'button-wrapper';
	public $maxfiles = false;
	public $accept = false;
	
	public function run()
	{
		list($name, $id) = $this->resolveNameID();
		
		if(isset($this->htmlOptions['id'])) {
			$id = $this->htmlOptions['id'];
		} else {
			$this->htmlOptions['id'] = $id;
		}
		
		if(isset($this->htmlOptions['name'])) {
			$name = $this->htmlOptions['name'];
		}
		
		if ($this->multiple && substr($name, -2) != '[]') {
			$name .= '[]';
		}
		
		$this->registerClientScript();
		
		if($this->hasModel()) {
			$value = $this->model->{$this->attribute};
		} else {
			$value = $this->value;
		}
		
		echo CHtml::openTag($this->container, array(
			'class' => $this->containerCssClass,
			'id' => 'file-select-' . $id,
		));
		if ($this->multiple) {
			echo CHtml::hiddenField(substr($name, 0, -2), '', array('id' => false));
		}
		$this->renderSelection($name, $value);
		echo CHtml::openTag('label', array('class' => $this->buttonCssClass));
		echo $this->buttonText;
		echo CHtml::fileField($name, '', array_merge($this->htmlOptions, array(
			'data-onload' => 'fileselect.init',
		)));
		echo CHtml::closeTag('label');
		echo CHtml::closeTag($this->container);
	}
	
	protected function registerClientScript()
	{
		$id = $this->htmlOptions['id'];
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile('/rc/js/file-select.js', CClientScript::POS_END);
		$options = CJSON::encode(array(
			'multiple' => $this->multiple,
			'container' => '.' . $this->containerCssClass,
			'itemCssClass' => $this->itemCssClass,
			'maxfiles' => $this->maxfiles,
			'accept' => $this->accept,
		));
		$cs->registerScript(__CLASS__. "#{$id}", 
			"$('#{$id}').fileSelect({$options});".
			"$(document.body).on('fileselect.init', '#{$id}', function() { $(this).fileSelect({$options}); });"
		);
	}
	
	protected function renderSelection($name, $value)
	{
		$value = $this->normalizeSelection($value);
		foreach ($value as $file) {
			echo CHtml::openTag('span', array(
				'class' => $this->itemCssClass,
				'data-type' => $file->mime,
			));
			echo CHtml::hiddenField($name, $file->id);
			echo CHtml::encode($file->title);
			echo CHtml::link('&times', '#', array('class' => 'delete'));
			echo CHtml::closeTag('span');
			if (!$this->multiple) {
				break;
			}
		}
	}
	
	protected function normalizeSelection($value)
	{
		if (empty($value)) {
			return array();
		}
		if (!is_array($value)) {
			$value = array($value);
		}
		$ids = array();
		$result = array();
		foreach ($value as $v) {
			if ($v instanceof File) {
				$result[] = $v;
			} else {
				$ids[] = $v;
			}
		}
		if (count($ids)) {
			foreach (File::model()->findAllByPk($ids) as $v) {
				$result[] = $v;
			}
		}
		return $result;
	}
}
