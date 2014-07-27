<?php

class ColorPicker extends CInputWidget
{
	public $registerStyles = true;
	public $colors = array(
		'#ac725e' => '#ac725e',
		'#d06b64' => '#d06b64',
		'#f83a22' => '#f83a22',
		'#fa573c' => '#fa573c',
		'#ff7537' => '#ff7537',
		'#ffad46' => '#ffad46',
		'#42d692' => '#42d692',
		'#16a765' => '#16a765',
		'#7bd148' => '#7bd148',
		'#b3dc6c' => '#b3dc6c',
		'#fbe983' => '#fbe983',
		'#fad165' => '#fad165',
		'#92e1c0' => '#92e1c0',
		'#9fe1e7' => '#9fe1e7',
		'#9fc6e7' => '#9fc6e7',
		'#4986e7' => '#4986e7',
		'#9a9cff' => '#9a9cff',
		'#b99aff' => '#b99aff',
		'#c2c2c2' => '#c2c2c2',
		'#cabdbf' => '#cabdbf',
		'#cca6ac' => '#cca6ac',
		'#f691b2' => '#f691b2',
		'#cd74e6' => '#cd74e6',
		'#a47ae2' => '#a47ae2',
	);
	
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

		$this->registerClientScript();
		
		if($this->hasModel()) {
			$value = $this->model->{$this->attribute};
		} else {
			$value = $this->value;
		}
		
		echo CHtml::dropdownList($name, $value, $this->colors);
	}
	
	protected function registerClientScript()
	{
		$id = $this->htmlOptions['id'];
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile('/rc/jquery-simplecolorpicker/jquery.simplecolorpicker.js', CClientScript::POS_END);
		if ($this->registerStyles) {
			$cs->registerCssFile('/rc/jquery-simplecolorpicker/jquery.simplecolorpicker.css');
		}
		$cs->registerScript(__CLASS__. "#$id", "$('#$id').simplecolorpicker({picker: true});");
	}
}
