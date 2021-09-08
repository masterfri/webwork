<?php

class CompletionReport extends CActiveRecord  
{
	public $date_from;
	public $date_to;
	public $projects;
	public $collect_jobs = 0;
	public $conversion_rate = 1;

	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{completion_reports}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'number' => Yii::t('completionReport', 'Number'),
            'date' => Yii::t('completionReport', 'Date'),
            'contract_number' => Yii::t('completionReport', 'Contract Number'),
            'contract_date' => Yii::t('completionReport', 'Contract Date'),
			'draft' => Yii::t('completionReport', 'Draft'),
			'performer_id' => Yii::t('completionReport', 'Performer'),
			'performer' => Yii::t('completionReport', 'Performer'),
            'contragent_id' => Yii::t('completionReport', 'Counterparty'),
			'contragent' => Yii::t('completionReport', 'Counterparty'),
			'time_created' => Yii::t('completionReport', 'Date Created'),
            'created_by_id' => Yii::t('completionReport', 'Created by'),
			'created_by' => Yii::t('completionReport', 'Created by'),
			'date_from' => Yii::t('completionReport', 'Date From'),
			'date_to' => Yii::t('completionReport', 'Date To'),
			'projects' => Yii::t('completionReport', 'Projects'),
			'conversion_rate' => Yii::t('completionReport', 'Conversion Rate'),
			'collect_jobs' => Yii::t('completionReport', 'Collect Jobs'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	draft', 
					'required', 'on' => 'update'),
			array('	draft', 
					'boolean', 'on' => 'update'),
			array('	number', 
					'numerical', 'min' => 1, 'integerOnly' => true, 'on' => 'create, update'),
			array('	number', 
					'required', 'on' => 'update'),
			array('	number', 
					'unique', 'on' => 'create, update', 'criteria' => $this->getUniqueCriteria()),
			array(' contract_number,
					performer_id,
					contragent_id,
					contract_date',
					'required', 'on' => 'create'),
			array(' date',
					'required', 'on' => 'create, update'),
			array(' date_from,
					date_to,
					contract_date',
					'date', 'format' => 'yyyy-MM-dd', 'on' => 'create'),
			array(' projects',
					'safe', 'on' => 'create'),
			array('	collect_jobs', 
					'boolean', 'on' => 'create'),
			array('	conversion_rate', 
					'numerical', 'min' => 0.01, 'on' => 'create'),
			array(' date,
					contract_date',
					'date', 'format' => 'yyyy-MM-dd', 'on' => 'create, update'),
			array('	contract_number', 
					'length', 'max' => 50, 'on' => 'create, update'),
			array(' performer_id,
					contragent_id,
					contract_number',
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'amount' => array(self::STAT, 'CompletedJob', 'report_id', 'select' => 'SUM(price * qty)'),
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'performer' => array(self::BELONGS_TO, 'User', 'performer_id'),
			'contragent' => array(self::BELONGS_TO, 'User', 'contragent_id'),
			'items' => array(self::HAS_MANY, 'CompletedJob', 'report_id'),
		);
	}
	
	public function scopes()
	{
		return array(
			'my' => array(
				'condition' => '(completion_reports.performer_id = :current_user_id OR completion_reports.contragent_id = :current_user_id OR completion_reports.created_by_id = :current_user_id)',
				'params' => array(
					':current_user_id' => Yii::app()->user->id,
				),
			),
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
					'performer',
					'contragent',
					'items' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
				),
			),
		);
	}

	protected function beforeSave()
	{
		if (parent::beforeSave()) {
			if ($this->isNewRecord && empty($this->number)) {
				$this->number = $this->pickNextNumber();
			}
			return true;
		}
		return false;
	}

	protected function afterSave()
	{
		if ($this->isNewRecord && $this->collect_jobs == 1) {
			$this->createJobs();
		}
		parent::afterSave();
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'completion_reports';
		$criteria->compare('completion_reports.contract_number', $this->contract_number, true);
		$criteria->compare('completion_reports.performer_id', $this->performer_id);
		$criteria->compare('completion_reports.contragent_id', $this->contragent_id);
		$criteria->with = array('performer', 'contragent');
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'completion_reports.time_created DESC',
				'attributes' => array(
					'performer' => array(
						'asc' => 'performer.real_name, performer.username',
						'desc' => 'performer.real_name DESC, performer.username DESC',
					),
					'contragent' => array(
						'asc' => 'contragent.real_name, contragent.username',
						'desc' => 'contragent.real_name DESC, contragent.username DESC',
					),
					'*',
				),
			),
		));
	}
	
	public function getItems($params=array())
	{
		$model = new CompletedJob('search');
		$model->unsetAttributes();
		$criteria = new CDbCriteria($params);
		$criteria->compare('completed_jobs.report_id', $this->id);
		$provider = $model->search($criteria);
		$provider->pagination = false;
		return $provider;
	}
	
	public function getFormattedNumber()
	{
		return sprintf('#%03d', $this->number);
	}
	
	public function __toString()
	{
		return $this->getFormattedNumber();
	}

	protected function createJobs()
	{
		$items = $this->collectCompletedJobs();
		$matrix = $this->performer->rate->getCompleteMatrix();
		while ($row = $items->read()) {
			$label = $row['description'];
			$label = mb_substr($label, 0, 200);
			$item = new CompletedJob('create');
			$item->report_id = $this->id;
			$item->price = isset($matrix[$row['activity_id']]) ? $matrix[$row['activity_id']]->hour_rate * $row['amount'] : 0;
			$item->qty = 1;
			if ($row['bonus'] != 0) {
				if ($row['bonus_type'] == Project::BONUS_ABSOLUTE) {
					$item->price += $row['amount'] * $row['bonus'];
				} elseif ($row['bonus_type'] == Project::BONUS_PERCENT) {
					$item->price += $item->price * ($row['bonus'] / 100);
				}
			}
			$item->price *= $this->conversion_rate;
			$item->name = $label;
			$item->save(false);
		}
		$items->close();
	}

	protected function collectCompletedJobs()
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'timeentry';
		$criteria->compare('timeentry.user_id', $this->performer_id);
		$criteria->select = 'timeentry.activity_id, SUM(timeentry.amount) AS amount, '.
							'IF (milestone.id IS NOT NULL, milestone.name, IF(task.id IS NOT NULL, task.name, activity.name)) AS description, '.
							'timeentry.task_id, project.bonus, project.bonus_type';
		$criteria->group = 'IF (milestone.id IS NOT NULL, CONCAT("m", milestone.id), IF(task.id IS NOT NULL, CONCAT("t", task.id), timeentry.activity_id))';
		$criteria->join = 'LEFT JOIN {{task}} task ON task.id = timeentry.task_id '.
						  'LEFT JOIN {{project}} project ON project.id = task.project_id '.
						  'LEFT JOIN {{activity}} activity ON activity.id = timeentry.activity_id '.
						  'LEFT JOIN {{milestone}} milestone ON milestone.id = task.milestone_id';
		$criteria->compare('timeentry.project_id', $this->projects);
		if ($this->date_from) {
			$criteria->compare('timeentry.date_created', ">={$this->date_from} 00:00:00");
		}
		if ($this->date_to) {
			$criteria->compare('timeentry.date_created', "<={$this->date_to} 23:59:59");
		}
		$builder = Yii::app()->db->commandBuilder;
		return $builder->createFindCommand(TimeEntry::model()->tableName(), $criteria)->query();
	}

	protected function pickNextNumber()
	{
		$criteria = new CDbCriteria();
		$criteria->select = 'MAX(`number`) AS `number`';
		$criteria->compare('contract_number', $this->contract_number);
		$criteria->compare('contragent_id', $this->contragent_id);
		$criteria->compare('performer_id', $this->performer_id);
		$last = $this->find($criteria);
		return $last->number ? $last->number + 1 : 1;
	}

	public function rememberRecentContract()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('performer_id', $this->performer_id);
		$criteria->order = 'id DESC';
		$criteria->limit = 1;
		$last = $this->find($criteria);
		if ($last !== null) {
			$this->contract_number = $last->contract_number;
			$this->contract_date = $last->contract_date;
			$this->contragent_id = $last->contragent_id;
		}
	}

	protected function getUniqueCriteria()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('contract_number', $this->contract_number);
		$criteria->compare('contragent_id', $this->contragent_id);
		$criteria->compare('performer_id', $this->performer_id);
		if (!$this->isNewRecord) {
			$criteria->compare('id', "<>{$this->id}");
		}
		return $criteria;
	}
}
