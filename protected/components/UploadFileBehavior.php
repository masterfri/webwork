<?php

class UploadFileBehavior extends CActiveRecordBehavior
{
	public $attributes;
	public $scenario;
	public $remove_orphan = true;
	protected $old_keys = array();
	protected $builder;
	
	public function afterValidate($event) 
	{
		$owner = $this->getOwner();
		
		if (!$this->checkScenario($owner)) {
			return;
		}
		
		$relations = $owner->relations();
		$relations_ok = false;
		if ($this->remove_orphan && !$owner->getIsNewRecord()) {
			$this->collectOldKeys($owner, $relations);
			$relations_ok = true;
		}
		foreach ($this->attributes as $attribute) {
			if (!$relations_ok) {
				$this->checkRelation($relations, $attribute);
			}
			if (!$owner->hasErrors($attribute)) {
				$ids = $this->uploadFiles($owner, $attribute);
				if (count($ids)) {
					if ($relations[$attribute][0] == CActiveRecord::MANY_MANY) {
						$value = $this->mergeFiles($owner, $attribute, $ids);
					} else {
						$value = current($ids);
					}
					$owner->$attribute = $value;
				}
			}
		}
	}
	
	public function afterSave($event)
	{
		$owner = $this->getOwner();
		if ($this->remove_orphan && !$owner->getIsNewRecord() && $this->checkScenario($owner)) {
			$relations = $owner->relations();
			foreach ($this->attributes as $attribute) {
				if (isset($this->old_keys[$attribute])) {
					if ($relations[$attribute][0] == CActiveRecord::MANY_MANY) {
						$value = $this->mergeFiles($owner, $attribute, array());
						$this->old_keys[$attribute] = array_diff($this->old_keys[$attribute], $value);
					} else {
						$value = $owner->getRelated($attribute);
						if ($this->old_keys[$attribute] == ($value instanceof File ? $value->id : $value)) {
							unset($this->old_keys[$attribute]);
						}
					}
				}
			}
			$this->deleteOrphanFiles();
		}
	}
	
	public function beforeDelete($event)
	{
		if ($this->remove_orphan) {
			$owner = $this->getOwner();
			$this->collectOldKeys($owner, $owner->relations());
			$this->deleteOrphanFiles();
		}
	}
	
	protected function deleteOrphanFiles()
	{
		$criteria = new CDbCriteria();
		foreach ($this->old_keys as $keys) {
			if (!empty($keys)) {
				$criteria->compare('id', $keys, 'OR');
			}
		}
		if (!empty($criteria->condition)) {
			foreach (File::model()->findAll($criteria) as $file) {
				$file->delete();
			}
		}
	}
	
	protected function collectOldKeys($owner, $relations)
	{
		$builder = $this->getCommandBuilder();
		foreach ($this->attributes as $attribute) {
			$this->checkRelation($relations, $attribute);
			if ($relations[$attribute][0] == CActiveRecord::MANY_MANY) {
				list($table, $thisfk, $otherfk) = $this->parseManyManyRelation($relations[$attribute]);
				$criteria = new CDbCriteria();
				$criteria->compare($thisfk, $owner->getPrimaryKey());
				$criteria->select = $otherfk;
				$this->old_keys[$attribute] = $builder->createFindCommand($table, $criteria)->queryColumn();
			} else {
				$this->old_keys[$attribute] = $owner->getAttribute($relations[$attribute][2]);
			}
		}
	}
	
	protected function parseManyManyRelation($relation)
	{
		if(!preg_match('/^\s*(.*?)\((.*)\)\s*$/', $relation[2], $matches)) {
			throw new CException('Invalid foreign key definition');
		}
		$foreignKeys = preg_split('/\s*,\s*/', $matches[2], -1, PREG_SPLIT_NO_EMPTY);
		if (count($foreignKeys) != 2) {
			throw new CException('Composite keys is not supportned yet');
		}
		return array($matches[1], $foreignKeys[0], $foreignKeys[1]);
	}
	
	protected function getCommandBuilder()
	{
		if (null === $this->builder) {
			$this->builder = $this->getOwner()->dbConnection->commandBuilder;
		}
		return $this->builder;
	}
	
	protected function checkScenario($owner)
	{
		if (is_array($this->scenario)) {
			if (!in_array($owner->getScenario(), $this->scenario)) {
				return false;
			}
		} elseif (is_string($this->scenario)) {
			if ($owner->getScenario() != $this->scenario) {
				return false;
			}
		}
		return true;
	}
	
	protected function checkRelation($relations, $attribute)
	{
		if (!isset($relations[$attribute])) {
			throw new Exception("Relation for `{$attribute}` is not defined");
		}
		if ($relations[$attribute][0] != CActiveRecord::MANY_MANY && $relations[$attribute][0] != CActiveRecord::BELONGS_TO) {
			throw new Exception("Relation type for `{$attribute}` is not supported");
		}
	}
	
	protected function uploadFiles($owner, $attribute)
	{
		$ids = array();
		foreach (File::model()->processUploading(CHtml::resolveName($owner, $attribute)) as $result) {
			if ($result instanceof File) {
				$ids[] = $result->id;
			}
		}
		return $ids;
	}
	
	protected function mergeFiles($owner, $attribute, $ids)
	{
		$owned = $owner->getRelated($attribute);
		if (!empty($owned)) {
			foreach ((array) $owned as $file) {
				if ($file instanceof File) {
					$ids[] = $file->id;
				} else {
					$ids[] = $file;
				}
			}
		}
		return $ids;
	}
}
