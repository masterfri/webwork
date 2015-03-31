<?php

class RequiredIf extends CRequiredValidator
{
	public $condition;
	public $jscondition;
	
	protected function validateAttribute($object, $attribute)
	{
		if ($this->condition && $this->evaluateExpression($this->condition, array(
			'model' => $object, 
			'attribute' => $attribute))) 
		{
			parent::validateAttribute($object, $attribute);
		}
	}
	
	public function clientValidateAttribute($object, $attribute)
	{
		$basic = parent::clientValidateAttribute($object, $attribute);
		if ($this->jscondition) {
			$basic = "if(" . CJavaScript::encode($this->jscondition) . ") { " . $basic . " }";
		}
		return $basic;
	}
}
