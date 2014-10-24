<?php

class UploadFileBehavior extends CActiveRecordBehavior
{
	public $attributes;
	public $scenario;
	
	public function afterValidate($event) 
	{
		$owner = $this->getOwner();
		
		if (is_array($this->scenario)) {
			if (!in_array($owner->getScenario(), $this->scenario)) {
				return;
			}
		} elseif (is_string($this->scenario)) {
			if ($owner->getScenario() != $this->scenario) {
				return;
			}
		}
		
		$relations = $owner->relations();
		foreach ($this->attributes as $attribute) {
			if (!$owner->hasErrors($attribute)) {
				$ids = array();
				foreach (File::model()->processUploading(CHtml::resolveName($owner, $attribute)) as $result) {
					if ($result instanceof File) {
						$ids[] = $result->id;
					}
				}
				if (count($ids)) {
					if (isset($relations[$attribute]) && $relations[$attribute][0] == CActiveRecord::MANY_MANY) {
						if (!empty($owner->$attribute)) {
							foreach ((array) $owner->$attribute as $file) {
								if ($file instanceof File) {
									$ids[] = $file->id;
								} else {
									$ids[] = $file;
								}
							}
						}
						$owner->$attribute = $ids;
					} else {
						$owner->$attribute = current($ids);
					}
				}
			}
		}
	}
}
