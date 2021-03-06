<?php

class AppEntity extends CActiveRecord  
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{app_entity}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by' => Yii::t('appEntity', 'Created by'),
			'description' => Yii::t('appEntity', 'Description'),
			'schemes' => Yii::t('appEntity', 'Schemes'),
			'module' => Yii::t('appEntity', 'Мodule'),
			'label' => Yii::t('appEntity', 'Label'),
			'name' => Yii::t('appEntity', 'Name'),
			'time_created' => Yii::t('appEntity', 'Date Created'),
		);
	}
	
	public function rules()
	{
		return array(
			array(' schemes',
					'required', 'on' => 'create, update'),
			array(' name',
					'required', 'on' => 'create, update'),
			array(' label',
					'required', 'on' => 'copyAsTemplate, updateTemplate'),
			array(' module,
					name', 
					'match', 'pattern' => '/^[a-z_][a-z0-9_]*$/i', 'on' => 'create, update'),
			array(' name', 
					'unique', 'criteria' => array('condition' => 'application_id = :application_id', 'params' => array(':application_id' => $this->application_id)), 'on' => 'create'),
			array(' name', 
					'unique', 'criteria' => array('condition' => 'id != :id AND application_id = :application_id', 'params' => array(':id' => $this->id, ':application_id' => $this->application_id)), 'on' => 'update'),
			array(' module,
					name', 
					'length', 'max' => 100, 'on' => 'create, update'),
			array(' label', 
					'length', 'max' => 100, 'on' => 'create, update, copyAsTemplate, updateTemplate'),
			array(' json_source', 
					'required', 'on' => 'create, update'),
			array(' description', 
					'length', 'max' => 16000, 'on' => 'create, update, copyAsTemplate, updateTemplate'),
			array(' plain_source',
					'required', 'on' => 'updateExpert, createExpert'),
			array(' plain_source',
					'validateSource', 'on' => 'updateExpert, createExpert'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'application' => array(self::BELONGS_TO, 'Application', 'application_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'StampBehavior',
				'create_time_attribute' => 'time_created',
				'created_by_attribute' => 'created_by',
			),
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'created_by',
				),
			),
		);
	}
	
	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->expert_mode != 1) {
				$this->producePlainSrc();
			}
			return true;
		}
		return false;
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'appEntity';
		$criteria->compare('appEntity.name', $this->name, true);
		
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'appEntity.name',
			),
		));
	}
	
	public function __toString()
	{
		return $this->name;
	}
	
	public function getSchemes()
	{
		return empty($this->json_schemes) ? array() : CJSON::decode($this->json_schemes);
	}
	
	public function setSchemes($value)
	{
		$this->json_schemes = CJSON::encode($value);
	}
	
	protected function producePlainSrc()
	{
		$lines = array();
		
		if ('' != trim($this->label)) {
			$lines[] = sprintf('/// %s', $this->label);
		}
		if ('' != trim($this->module)) {
			$lines[] = sprintf('/// @module %s', $this->module);
		}
		if ('' != trim($this->description)) {
			$lines[] = '///';
			foreach (explode("\n", $this->description) as $line) {
				$lines[] = sprintf('/// %s', $line);
			}
			$lines[] = '///';
		}
		
		$lines[] = sprintf('model `%s` scheme %s:', $this->name, implode(', ', $this->getSchemes()));
		
		$json = CJSON::decode($this->json_source);
		
		if (isset($json['attributes'])) {
			foreach ($json['attributes'] as $attribute) {
				$opts = array();
				$intopts = array();
				$lines[] = '';
				$lines[] = sprintf('    /// %s', $attribute['label']);
				if (isset($attribute['required']) && $attribute['required']) {
					$lines[] = '    /// @required';
				}
				if (isset($attribute['sortable']) && $attribute['sortable']) {
					$lines[] = '    /// @sortable';
				}
				if (isset($attribute['readonly']) && $attribute['readonly']) {
					$lines[] = '    /// @readonly';
				}
				if (isset($attribute['relation'])) {
					$lines[] = sprintf('    /// @relation %s', $attribute['relation']);
				}
				if (isset($attribute['backref'])) {
					$lines[] = sprintf('    /// @backreference %s', $attribute['backref']);
				}
				if (isset($attribute['description']) && '' != trim($attribute['description'])) {
					$lines[] = '    ///';
					foreach (explode("\n", $attribute['description']) as $line) {
						$lines[] = sprintf('    /// %s', $line);
					}
					$lines[] = '    ///';
				}
				$attrcode = sprintf('    attr `%s`', $attribute['name']);
				if (isset($attribute['collection']) && $attribute['collection']) {
					$attrcode .= ' collection';
				}
				if (isset($attribute['unsigned']) && $attribute['unsigned'] && ($attribute['type'] == 'int' || $attribute['type'] == 'decimal')) {
					$attrcode .= ' unsigned';
				}
				if (isset($attribute['tableview']) && $attribute['tableview']) {
					$lines[] = '    /// @tableview';
				}
				if (isset($attribute['detailview']) && $attribute['detailview']) {
					$lines[] = '    /// @detailedview';
				}
				if (isset($attribute['subordinate']) && $attribute['subordinate']) {
					$lines[] = '    /// @subordinate';
				}
				$attrcode .= sprintf(' %s', $attribute['type']);
				switch ($attribute['type']) {
					case 'int':
					case 'char':
						if (isset($attribute['size']) && '' != trim($attribute['size'])) {
							$attrcode .= sprintf('(%s)', intval($attribute['size']));
						}
						break;
					case 'decimal':
						if (isset($attribute['size']) && '' != trim($attribute['size'])) {
							$attrcode .= sprintf('(%s)', implode(',', array_map('intval', explode(',', $attribute['size'], 2))));
						}
						break;
					case 'option';
						$lastkey = 0;
						foreach (explode("\n", $attribute['options']) as $line) {
							$line = trim($line);
							if (preg_match('/^([\d]+)\s*[:](.+)$/', $line, $matches)) {
								$lastkey = (int) $matches[1];
								$opts[$lastkey] = sprintf('%d = "%s"', $lastkey, addslashes(trim($matches[2])));
								$intopts[$lastkey] = trim($matches[2]);
							} else {
								$lastkey++;
								$opts[$lastkey] = sprintf('%d = "%s"', $lastkey, addslashes($line));
								$intopts[$lastkey] = $line;
							}
						}
						$attrcode .= sprintf('(%s)', implode(', ', $opts));
						break;
					case 'enum';
						foreach (explode("\n", $attribute['options']) as $line) {
							$line = trim($line);
							$opts[] = sprintf('"%s"', addslashes($line));
						}
						$attrcode .= sprintf('(%s)', implode(', ', $opts));
						break;
				}
				if (!isset($attribute['collection']) || !$attribute['collection']) {
					if (isset($attribute['default']) && '' != trim($attribute['default'])) {
						switch ($attribute['type']) {
							case 'int':
								$attrcode .= sprintf(' = %s', (int) $attribute['default']);
								break;
							case 'bool':
								$attrcode .= sprintf(' = %s', in_array(strtolower(trim($attribute['default'])), array('1', 'true')) ? 'true' : 'false');
								break;
							case 'decimal':
								$attrcode .= sprintf(' = %s', (float) $attribute['default']);
								break;
							case 'option';
								$key = (int) trim($attribute['default']);
								if (isset($intopts[$key])) {
									$attrcode .= sprintf(' = "%s"', addslashes($intopts[$key]));
								}
								break;
							default:
								$attrcode .= sprintf(' = "%s"', addslashes($attribute['default']));
								break;
						}
					}
				}
				$lines[] = $attrcode . ';';
			}
		}
		
		$this->plain_source = implode("\n", $lines);
	}
	
	public function getEntityAttributes()
	{
		$src = CJSON::decode($this->json_source);
		return isset($src['attributes']) ? $src['attributes'] : array();
	}
	
	public function validateSource()
	{
		if ($this->plain_source != '') {
			try {
				$models = $this->application->getCF()->parseEntity($this);
				if (count($models) != 1) {
					$this->addError('plain_source', Yii::t('appEntity', 'You must define exact one model'));
				} else {
					$model = current($models);
					$this->name = $model->getName();
					$criteria = new CDbCriteria();
					$criteria->addCondition('name = :name AND application_id = :application_id');
					$criteria->params[':name'] = $this->name;
					$criteria->params[':application_id'] = $this->application_id;
					if (!$this->isNewRecord) {
						$criteria->addCondition('id != :id');
						$criteria->params[':id'] = $this->id;
					}
					if ($this->count($criteria) > 0) {
						$this->addError('plain_source', Yii::t('appEntity', 'This model name is already in use'));
					}
				}
			} catch (Exception $e) {
				$this->addError('plain_source', $e->getMessage());
			}
		}
	}
}
