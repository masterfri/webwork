<?php

class PushApplicationForm extends CFormModel
{
	const RESOLVE_OVERWRITE = 1;
	const RESOLVE_IGNORE = 2;
	
	public $branch;
	public $message;
	public $resolves = array();
	
	public function rules()
	{
		return array(
			array(' branch,
					message',
					'required'),
			array(' branch',
					'match', 'pattern' => '/^[a-z0-9_-]+$/i'),
			array(' resolves',
					'safe'),
		);
	}
	
	public function attributeLabels()
	{
		return array(
			'branch' => Yii::t('application', 'Branch'),
			'message' => Yii::t('application', 'Message'),
		);
	}
	
	public function setConflictedFiles($list)
	{
		$this->resolves = array();
		foreach ((array) $list as $file) {
			$this->resolves[$file] = self::RESOLVE_IGNORE;
		}
	}
	
	public function hasConflictedFiles()
	{
		return !empty($this->resolves);
	}
	
	public function getResolutionFiles($resolution)
	{
		$result = array();
		foreach ($this->resolves as $file => $value) {
			if ($value == $resolution) {
				$result[] = $file;
			}
		}
		return $result;
	}
}
