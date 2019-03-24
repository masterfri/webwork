<?php

class Question extends CActiveRecord  
{
	const LEVEL_LOW = 1;
	const LEVEL_MODERATE = 2;
	const LEVEL_HIGH = 3;
	const LEVEL_TOP = 4;
	
	protected static $levels;
	public $time_questioned;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{question}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by_id' => Yii::t('question', 'Created by'),
			'created_by' => Yii::t('question', 'Created by'),
			'level' => Yii::t('question', 'Level'),
			'levelName' => Yii::t('question', 'Level'),
			'category_id' => Yii::t('question', 'Category'),
			'category' => Yii::t('question', 'Category'),
			'text' => Yii::t('question', 'Question'),
			'time' => Yii::t('question', 'Time'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	text', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array('	text,
					level,
					time,
					category_id', 
					'required', 'on' => 'create, update'),
			array('	time', 
					'numerical', 'min' => 10, 'integerOnly' => true, 'on' => 'create, update'),
			array('	category_id,
					level,
					text', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'category' => array(self::BELONGS_TO, 'QuestionCategory', 'category_id'),
			'answers' => array(self::HAS_MANY, 'Answer', 'question_id'),
			'candidate_answers' => array(self::HAS_MANY, 'CandidateAnswer', 'question_id'),
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
					'category',
					'answers' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
					'candidate_answers' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
				),
			),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'question';
		$criteria->compare('question.category_id', $this->category_id);
		$criteria->compare('question.level', $this->level);
		$criteria->compare('question.text', $this->text, true);
		$criteria->with = array('category');
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'attributes' => array(
					'category' => array(
						'asc' => 'category.name ASC',
						'desc' => 'category.name DESC',
					),
					'levelName' => array(
						'asc' => 'question.level ASC',
						'desc' => 'question.level DESC',
					),
					'*',
				),
			),
		));
	}
	
	public function __toString()
	{
		return $this->text;
	}
	
	public static function getListLevels()
	{
		if (null === self::$levels) {
			self::$levels = array(
				self::LEVEL_LOW => Yii::t('question', 'Low'),
				self::LEVEL_MODERATE => Yii::t('question', 'Moderate'),
				self::LEVEL_HIGH => Yii::t('question', 'High'),
				self::LEVEL_TOP => Yii::t('question', 'Top'),
			);
		}
		return self::$levels;
	}
	
	public function getLevelName()
	{
		return array_key_exists($this->level, self::getListLevels()) ? self::$levels[$this->level] : '';
	}
	
	public function getAnswers($params=array())
	{
		$model = new Answer('search');
		$model->unsetAttributes();
		$criteria = new CDbCriteria($params);
		$criteria->compare('answer.question_id', $this->id);
		$provider = $model->search($criteria);
		$provider->pagination = false;
		return $provider;
	}
	
	public function getShuffledAnswers()
	{
		return $this->getAnswers(array(
			'order' => new CDbExpression('RAND()'),
		));
	}
	
	public function getMaxScore()
	{
		$max = 0;
		foreach ($this->answers as $answer) {
			$max = max($max, $answer->score);
		}
		return $max;
	}
}
