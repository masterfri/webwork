<?php

class DropdownMenuSelect extends CInputWidget
{
	const TYPE_HIDDEN = 1;
	const TYPE_TEXT_FIELD = 2;
	const TYPE_STATIC_TEXT = 3;
	
	public $options = array();
	public $labels = false;
	public $htmlEncodeOptions = true;
	public $multiple = false;
	public $multipleLabelSeparator = ', ';
	public $multipleValueSeparator = ',';
	public $emptyOption = '';
	public $emptyLabel = '';
	public $type = self::TYPE_STATIC_TEXT;
	public $button = '<span class="caret"></span>';
	public $template = '{button} {label} {dropdown}';
	public $doneBtnText = 'Done';
	public $buttonHtmlOptions = array();
	public $dropdownHtmlOptions = array();
	public $hiddenHtmlOptions = array();
	public $doneBtnHtmlOptions = array();
	
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
		
		$this->buttonHtmlOptions['id'] = $id . '_button';
		$this->hiddenHtmlOptions['id'] = $id . '_hidden';
		$this->buttonHtmlOptions['data-toggle'] = 'dropdown';
		$this->buttonHtmlOptions['type'] = 'button';
		$this->dropdownHtmlOptions['id'] = $id . '_dropdown';
		if (!isset($this->dropdownHtmlOptions['class'])) {
			$this->dropdownHtmlOptions['class'] = 'dropdown-menu dropdown-menu-selectable';
		} else {
			$this->dropdownHtmlOptions['class'] .= ' dropdown-menu dropdown-menu-selectable';
		}
		$this->dropdownHtmlOptions['role'] = 'menu';
		$this->dropdownHtmlOptions['aria-labelledby'] = 'dLabel';
		
		if($this->hasModel()) {
			$value = $this->model->{$this->attribute};
		} else {
			$value = $this->value;
		}
		
		if ($this->multiple && !is_array($value)) {
			$value = empty($value) ? array() : array($value);
		}
		
		if ($this->htmlEncodeOptions) {
			$this->emptyOption = CHtml::encode($this->emptyOption);
			foreach ($this->options as $key => $option) {
				$this->options[$key] = CHtml::encode($option);
			}
		}
		
		echo CHtml::openTag('div', array('class' => 'dropdown'));
		
		$buttonHtml = CHtml::tag('button', $this->buttonHtmlOptions, $this->button);

		ob_start();
		if (self::TYPE_TEXT_FIELD == $this->type) {
			$this->htmlOptions['readonly'] = true;
			$v = $this->multiple ? implode($this->multipleValueSeparator, $value) : $value;
			echo CHtml::textField($name, $v, $this->htmlOptions);
		} else {
			if (self::TYPE_HIDDEN == $this->type) {
				if ($this->multiple) {
					echo CHtml::hiddenField($name, '');
					$name .= '[]';
					$this->hiddenHtmlOptions['style'] = 'display: none;';
					$this->hiddenHtmlOptions['data-name'] = $name;
					echo CHtml::openTag('div', $this->hiddenHtmlOptions);
					foreach ($value as $v) {
						echo CHtml::hiddenField($name, $v);
					}
					echo CHtml::closeTag('div');
				} else {
					echo CHtml::hiddenField($name, $value, $this->hiddenHtmlOptions);
				}
			}
			if ($this->multiple) {
				$label = array();
				foreach ($value as $v) {
					if (false !== $this->labels) {
						if (isset($this->labels[$v])) {
							$label[] = $this->labels[$v];
						}
					} else {
						if (isset($this->options[$v])) {
							$label[] = $this->options[$v];
						}
					}
				}
				$label = count($label) ? implode($this->multipleLabelSeparator, $label) : $this->emptyLabel;
			} else {
				if (false !== $this->labels) {
					$label = isset($this->labels[$value]) ? $this->labels[$value] : $this->emptyLabel;
				} else {
					$label = isset($this->options[$value]) ? $this->options[$value] : $this->emptyLabel;
				}
			}
			$container = 'span';
			if (isset($this->htmlOptions['container'])) {
				$container = $this->htmlOptions['container'];
				unset($this->htmlOptions['container']);
			}
			echo CHtml::tag($container, $this->htmlOptions, $label);
		}
		$labelHtml = ob_get_clean();
		
		ob_start();
		echo CHtml::openTag('ul', $this->dropdownHtmlOptions);
		if ($this->emptyOption) {
			echo CHtml::openTag('li', array(
				'role' => 'presentation',
				'class' => 'null',
			));
			echo CHtml::link($this->emptyOption, 'javascript:void(0)', array(
				'role' => 'menuitem',
				'data-value' => '',
			));
			echo CHtml::closeTag('li');
			echo CHtml::tag('li', array(
				'role' => 'presentation',
				'class' => 'divider',
			), '');
		}
		foreach ($this->options as $key => $option) {
			echo CHtml::openTag('li', array(
				'role' => 'presentation',
				'class' => ($this->multiple ? in_array($key, $value) : $key == $value) ? 'selected' : '',
			));
			echo CHtml::link($option, 'javascript:void(0)', array(
				'role' => 'menuitem',
				'data-value' => CHtml::encode($key),
			));
			echo CHtml::closeTag('li');
		}
		if ($this->multiple) {
			echo CHtml::openTag('li', array(
				'role' => 'presentation',
				'class' => 'button',
			));
			echo CHtml::tag('button', $this->doneBtnHtmlOptions, $this->doneBtnText);
			echo CHtml::closeTag('li');
		}
		echo CHtml::closeTag('ul');
		$dropdownHtml = ob_get_clean();
		
		echo strtr($this->template, array(
			'{button}' => $buttonHtml,
			'{label}' => $labelHtml,
			'{dropdown}' => $dropdownHtml,
		));
		
		echo CHtml::closeTag('div');
		
		$this->registerClientScript();
	}
	
	protected function registerClientScript()
	{
		$id = $this->htmlOptions['id'];
		$jsoptions = CJSON::encode(array(
			'button' => $this->buttonHtmlOptions['id'],
			'dropdown' => $this->dropdownHtmlOptions['id'],
			'hidden' => $this->hiddenHtmlOptions['id'],
			'emptyOption' => $this->emptyOption,
			'emptyLabel' => $this->emptyLabel,
			'options' => $this->options,
			'labels' => $this->labels,
			'multiple' => $this->multiple,
			'multipleLabelSeparator' => $this->multipleLabelSeparator,
			'multipleValueSeparator' => $this->multipleValueSeparator,
			'type' => $this->type,
		));
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$cs->registerScriptFile('/rc/bootstrap/js/bootstrap.js', CClientScript::POS_END);
		$cs->registerScriptFile('/rc/js/dropdown-menu-select.js', CClientScript::POS_END);
		$cs->registerScript(__CLASS__. "#$id", 
		"(function() {
			$('#$id').dropdownMenuSelect($jsoptions);
		})();");
	}
}
