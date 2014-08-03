<?php

class DatePicker extends CInputWidget
{
	public $registerStyles = true;
	public $format = 'd/m/Y';
	
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
		$this->htmlOptions['readonly'] = true;
		$hiddenHtmlOptions = array('id' => $id);
		$displayHtmlOptions = array_merge($this->htmlOptions, array('id' => $id . '_display'));

		$this->registerClientScript();
		
		if($this->hasModel()) {
			$value = $this->model->{$this->attribute};
		} else {
			$value = $this->value;
		}
		
		if ('0000-00-00' == $value || '' == $value) {
			$value = '';
			$fvalue = '';
		} else {
			$fvalue = date($this->format, strtotime($value));
		}
		
		echo CHtml::openTag('div', array(
			'class' => 'input-group',
		));
		echo CHtml::openTag('span', array(
			'class' => 'input-group-btn input-group-icon',
		)); 
		echo CHtml::openTag('button', array(
			'type' => 'button',
			'class' => 'btn btn-default',
			'data-date' => $fvalue,
			'id' => $id . '_btn_open',
		));
		echo ' ';
		echo CHtml::tag('span', array(
			'class' => 'glyphicon glyphicon-calendar',
		), '');
		echo CHtml::closeTag('button');
		echo CHtml::openTag('button', array(
			'type' => 'button', 
			'class' => 'btn btn-default',
			'id' => $id . '_btn_clear',
		));
		echo CHtml::tag('span', array(
			'class' => 'glyphicon glyphicon-remove',
		), '');
		echo CHtml::closeTag('button');
		echo CHtml::closeTag('span');
		echo CHtml::textField(null, $fvalue, $displayHtmlOptions);
		echo CHtml::closeTag('div');
		echo CHtml::hiddenField($name, $value, $hiddenHtmlOptions);
	}
	
	protected function registerClientScript()
	{
		$jformat = strtr($this->format, array(
			'Y' => 'yyyy',
			'y' => 'yy',
			'd' => 'dd',
			'j' => 'd',
			'm' => 'mm',
			'n' => 'm',
		));
		
		$id = $this->htmlOptions['id'];
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile('/rc/bootstrap/js/bootstrap.js', CClientScript::POS_END);
		$cs->registerScriptFile('/rc/datepicker/js/bootstrap-datepicker.js', CClientScript::POS_END);
		if ($this->registerStyles) {
			$cs->registerCssFile('/rc/datepicker/css/datepicker.css');
		}
		$cs->registerScript(__CLASS__. "#$id", 
		"(function() {
			var b = $('#{$id}_btn_open'), d = $('#{$id}_display'), c = $('#{$id}_btn_clear'), i = $('#{$id}');
			b.datepicker({format:'$jformat'}).on('changeDate', function(ev) {
				d.val(b.data('date'));
				i.val([ev.date.getFullYear(), ((ev.date.getMonth() >= 9 ? '' : '0') + (ev.date.getMonth() + 1)), ((ev.date.getDate() >= 10 ? '' : '0') +  ev.date.getDate())].join('-'));
				b.datepicker('hide');
			}); 
			c.click(function() { d.val(''); i.val(''); });
		})();");
	}
}
