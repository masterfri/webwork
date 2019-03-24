<?php

class CandidateAnswer extends CActiveRecord  
{
	const EXPIRE_DELAY = 3;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{candidate_answer}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'question' => Yii::t('candidate_answer', 'Question'),
			'answer' => Yii::t('candidate_answer', 'Answer'),
			'answer' => Yii::t('candidate_answer', 'Answer'),
			'time_questioned' => Yii::t('candidate_answer', 'Time Questioned'),
			'time_answered' => Yii::t('candidate_answer', 'Time Answered'),
		);
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'candidate_answer';
		$criteria->compare('candidate_answer.candidate_id', $this->candidate_id);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}
	
	public function relations()
	{
		return array(
			'question' => array(self::BELONGS_TO, 'Question', 'question_id'),
			'answer' => array(self::BELONGS_TO, 'Answer', 'answer_id'),
		);
	}
	
	public function behaviors()
	{
		return array(
			array(
				'class' => 'RelationBehavior',
				'attributes' => array(
					'question',
					'answer',
				),
			),
		);
	}
	
	public function isExpired()
	{
		return $this->getAnswerDelay() > 0;
	}
	
	public function getAnswerTime()
	{
		$start = strtotime($this->time_questioned);
		$end = strtotime($this->time_answered);
		return $end - $start;
	}
	
	public function getAnswerDelay()
	{
		return max(0, $this->getAnswerTime() - ($this->question->time + self::EXPIRE_DELAY));
	}
}
