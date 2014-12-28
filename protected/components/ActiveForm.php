<?php

class ActiveForm extends CActiveForm
{
	public function checkBoxList($model, $attribute, $data, $htmlOptions=array())
	{
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		$selection = CHtml::resolveValue($model, $attribute);
		if($model->hasErrors($attribute) && !empty(CHtml::$errorCss)) {
			if(isset($htmlOptions['class'])) {
				$htmlOptions['class'] .= ' ' . CHtml::$errorCss;
			} else {
				$htmlOptions['class'] = CHtml::$errorCss;
			}
		}
		foreach ($selection as $n => $value) {
			if ($value instanceof CActiveRecord) {
				$selection[$n] = $value->getPrimaryKey();
			}
		}
		$name = $htmlOptions['name'];
		unset($htmlOptions['name']);
		if(array_key_exists('uncheckValue', $htmlOptions)) {
			$uncheck = $htmlOptions['uncheckValue'];
			unset($htmlOptions['uncheckValue']);
		} else {
			$uncheck = '';
		}
		$hiddenOptions = isset($htmlOptions['id']) ? 
			array('id' => CHtml::ID_PREFIX . $htmlOptions['id']) : 
			array('id' => false);
		$hidden = $uncheck !== null ? CHtml::hiddenField($name, $uncheck, $hiddenOptions) : '';
		return $hidden . CHtml::checkBoxList($name, $selection, $data, $htmlOptions);
	}
	
	public function tagField($model, $attribute, $data, $htmlOptions=array())
	{
		return $this->selectField($model, $attribute, $data, array_merge($htmlOptions, array(
			'multiple' => true,
		)));
	}
	
	public function selectField($model, $attribute, $data, $htmlOptions=array())
	{
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile('/rc/select2/select2.js');
		$cs->registerCssFile('/rc/select2/select2.css');
		$cs->registerCssFile('/rc/select2/select2-bootstrap.css');
		
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		
		$htmlOptions['style'] = 'width: 100%;';
		$id = $htmlOptions['id'];
		
		$opts = array();
		$is_remote = false;
		if (isset($htmlOptions['ajax'])) {
			$opts = array(
				'ajax' => array_merge(array(
					'dataType' => 'json',
					'quietMillis' => 500,
					'data' => 'js:function(t, p) { return {query: t, page: p}; }',
					'results' => 'js:function(d, p) { return {results: d}; }',
				), $htmlOptions['ajax']),
			);
			$is_remote = true;
			unset($htmlOptions['ajax']);
			if (isset($htmlOptions['multiple'])) {
				$opts['multiple'] = true;
			}
		}
		$opts = CJavaScript::encode($opts);
		
		$cs->registerScript("select2{$id}", "$('#{$id}').select2($opts);");
		
		if ($is_remote) {
			if (isset($htmlOptions['multiple'])) {
				$htmlOptions['value'] = '';
				$name = $htmlOptions['name'];
				$htmlOptions['name'] = false;
				$cs->registerScript("select2{$id}split", "$('#{$id}').on('change', function(e) {
					$(this).next('.select2-hidden-multi').remove();
					var h = $('<span style=\"display:none;\" class=\"select2-hidden-multi\"></span>').insertAfter(this);
					$(e.val).each(function() { h.append('<input type=\"hidden\" name=\"{$name}[]\" value=\"' + this + '\">'); });
				});");
			}
			return $this->hiddenField($model, $attribute, $htmlOptions);
		} else {
			return $this->dropdownList($model, $attribute, $data, $htmlOptions);
		}
	}
	
	public function markdownField($model, $attribute, $htmlOptions=array())
	{
		$cs = Yii::app()->clientScript;
		$cs->registerScriptFile('/rc/markdown/markdown.js');
		$cs->registerScriptFile('/rc/toopay-bootstrap-markdown/js/bootstrap-markdown.js');
		$cs->registerCssFile('/rc/toopay-bootstrap-markdown/css/bootstrap-markdown.min.css');
		CHtml::resolveNameID($model, $attribute, $htmlOptions);
		$id = $htmlOptions['id'];
		$htmlOptions['data-onload'] = 'markdown.init';
		$cs->registerScript("markdownfield{$id}", "$(document.body).on('markdown.init', '#{$id}', function() { $(this).markdown({resize: 'vertical'}); }); $('#{$id}').trigger('markdown.init');");
		return $this->textArea($model, $attribute, $htmlOptions);
	}
	
	public function colorField($model, $attribute, $htmlOptions=array())
	{
		return $this->controller->widget('ColorPicker', array(
			'model' => $model,
			'attribute' => $attribute,
			'htmlOptions' => $htmlOptions,
		), true);
	}
	
	public function dateField($model, $attribute, $htmlOptions=array())
	{
		$options = $this->extractOptions(array(
			'format' => 'd/m/Y',
			'mode' => 'days',
		), $htmlOptions);
		
		return $this->controller->widget('DatePicker', array(
			'model' => $model,
			'attribute' => $attribute,
			'htmlOptions' => $htmlOptions,
			'format' => $options['format'],
			'mode' => $options['mode'],
		), true);
	}
	
	public function fileSelectField($model, $attribute, $htmlOptions=array())
	{
		$options = $this->extractOptions(array(
			'container' => 'span',
			'containerCssClass' => 'file-select-container',
			'itemCssClass' => 'file-select-item',
			'multiple' => false,
			'buttonText' => 'Select file',
			'buttonCssClass' => 'button-wrapper',
			'maxfiles' => false,
			'accept' => false,
		), $htmlOptions);

		return $this->controller->widget('FileSelect', array_merge($options, array(
			'model' => $model,
			'attribute' => $attribute,
			'htmlOptions' => $htmlOptions,
		)), true);
	}
	
	public function error($model, $attribute, $htmlOptions=array(), $enableAjaxValidation=true, $enableClientValidation=true)
	{
		if(!$this->enableAjaxValidation) {
			$enableAjaxValidation = false;
		}
		
		if(!$this->enableClientValidation) {
			$enableClientValidation = false;
		}

		if(!isset($htmlOptions['class'])) {
			$htmlOptions['class'] = $this->errorMessageCssClass;
		}

		if(!$enableAjaxValidation && !$enableClientValidation) {
			return CHtml::error($model, $attribute, $htmlOptions);
		}

		$id = isset($htmlOptions['attributeID']) ? $htmlOptions['attributeID'] : CHtml::activeId($model, $attribute);
		$inputID = isset($htmlOptions['inputID']) ? $htmlOptions['inputID'] : $id;
		unset($htmlOptions['inputID']);
		unset($htmlOptions['attributeID']);
		if(!isset($htmlOptions['id'])) {
			$htmlOptions['id'] = $inputID.'_em_';
		}

		$option = array(
			'id' => $id,
			'inputID' => $inputID,
			'errorID' => $htmlOptions['id'],
			'model' => get_class($model),
			'name' => $attribute,
			'enableAjaxValidation' => $enableAjaxValidation,
		);

		$optionNames = array(
			'validationDelay',
			'validateOnChange',
			'validateOnType',
			'hideErrorMessage',
			'inputContainer',
			'errorCssClass',
			'successCssClass',
			'validatingCssClass',
			'beforeValidateAttribute',
			'afterValidateAttribute',
		);
		
		foreach($optionNames as $name) {
			if(isset($htmlOptions[$name])) {
				$option[$name] = $htmlOptions[$name];
				unset($htmlOptions[$name]);
			}
		}
		
		if($model instanceof CActiveRecord && !$model->isNewRecord) {
			$option['status'] = 1;
		}

		if($enableClientValidation) {
			$validators = isset($htmlOptions['clientValidation']) ? array($htmlOptions['clientValidation']) : array();
			unset($htmlOptions['clientValidation']);

			$attributeName = $attribute;
			if(($pos = strrpos($attribute,']')) !== false && $pos !== strlen($attribute) - 1) {
				$attributeName = substr($attribute, $pos + 1);
			}

			foreach($model->getValidators($attributeName) as $validator) {
				if($validator->enableClientValidation) {
					if(($js = $validator->clientValidateAttribute($model,$attributeName))!='') {
						$validators[] = $js;
					}
				}
			}
			
			if($validators !== array()) {
				$option['clientValidation'] = new CJavaScriptExpression("function(value, messages, attribute) {\n" . implode("\n", $validators) . "\n}");
			}
		}

		$html = CHtml::error($model, $attribute, $htmlOptions);
		
		if($html === '') {
			if(isset($htmlOptions['style'])) {
				$htmlOptions['style'] = rtrim($htmlOptions['style'], ';') . ';display:none';
			} else {
				$htmlOptions['style'] = 'display:none';
			}
			$html = CHtml::tag(CHtml::$errorContainerTag, $htmlOptions, '');
		}

		$this->attributes[$inputID] = $option;
		
		return $html;
	}
	
	protected function extractOptions($options, &$source)
	{
		$result = array();
		foreach ($options as $key => $default) {
			if (isset($source[$key])) {
				$result[$key] = $source[$key];
				unset($source[$key]);
			} else {
				$result[$key] = $default;
			}
		}
		return $result;
	}
}
