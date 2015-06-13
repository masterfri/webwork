<?php

class TrackAttributeBehavior extends CActiveRecordBehavior
{
	public $attributes;
	public $callback;
	
	protected $_values;
	
	public function afterFind($event)
	{
		if (null === $this->attributes) {
			throw new CException('Attributes are not set');
		}
		foreach ((array) $this->attributes as $attribute) {
			$this->_values[$attribute] = $this->owner->$attribute;
		}
	}
	
	public function afterSave($event)
	{
		if (null === $this->attributes) {
			throw new CException('Attributes are not set');
		}
		if (!is_callable($this->callback)) {
			throw new CException('Invalid callback given');
		}
		$changed = array();
		foreach ((array) $this->attributes as $attribute) {
			$value = $this->owner->$attribute;
			if (!isset($this->_values[$attribute])) {
				$changed[$attribute] = array(null, $value);
				$this->_values[$attribute] = $value;
			} elseif ($this->_values[$attribute] != $value) {
				$changed[$attribute] = array($this->_values[$attribute], $value);
				$this->_values[$attribute] = $value;
			}
		}
		if ($changed !== array()) {
			call_user_func($this->callback, $this->owner, $changed);
		}
	}
}
