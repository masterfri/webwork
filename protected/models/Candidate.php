<?php

class Candidate extends CActiveRecord  
{
	const LEVEL_LOW = 1;
	const LEVEL_MODERATE = 2;
	const LEVEL_HIGH = 3;
	const LEVEL_TOP = 4;
	
	protected static $levels;
	protected static $locales;
	
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	public function tableName()
	{
		return '{{candidate}}';
	}
	
	public function attributeLabels()
	{
		return array(
			'created_by_id' => Yii::t('candidate', 'Created by'),
			'created_by' => Yii::t('candidate', 'Created by'),
			'examination_link' => Yii::t('candidate', 'Examination Link'),
			'level' => Yii::t('candidate', 'Level'),
			'levelName' => Yii::t('candidate', 'Level'),
			'name' => Yii::t('candidate', 'Name'),
			'notes' => Yii::t('candidate', 'Notes'),
			'questions_limit' => Yii::t('candidate', 'Questions Limit'),
			'time_created' => Yii::t('candidate', 'Date Created'),
			'time_examine_ended' => Yii::t('candidate', 'Examine Ended'),
			'time_examine_started' => Yii::t('candidate', 'Examine Started'),
			'examineEnded' => Yii::t('candidate', 'Examine Ended'),
			'examineStarted' => Yii::t('candidate', 'Examine Started'),
			'score' => Yii::t('candidate', 'Score'),
			'categories' => Yii::t('candidate', 'Categories'),
			'abandoned' => Yii::t('candidate', 'Abandoned'),
			'lang' => Yii::t('candidate', 'Language'),
			'localeName' => Yii::t('candidate', 'Language'),
			'questionsAvailable' => Yii::t('candidate', 'Questions Available'),
		);
	}
	
	public function rules()
	{
		return array(
			array('	notes', 
					'length', 'max' => 16000, 'on' => 'create, update'),
			array('	name', 
					'length', 'max' => 200, 'on' => 'create, update'),
			array('	name,
					level,
					questions_limit,
					categories,
					lang', 
					'required', 'on' => 'create, update'),
			array('	questions_limit', 
					'numerical', 'min' => 1, 'max' => 100, 'integerOnly' => true, 'on' => 'create, update'),
			array('	name', 
					'safe', 'on' => 'search'),
		);
	}
	
	public function relations()
	{
		return array(
			'created_by' => array(self::BELONGS_TO, 'User', 'created_by_id'),
			'categories' => array(self::MANY_MANY, 'QuestionCategory', '{{candidate_categories}}(candidate_id,category_id)'),
			'results' => array(self::HAS_MANY, 'CandidateAnswer', 'candidate_id'),
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
					'categories',
					'results' => array(
						'cascadeDelete' => true,
						'quickDelete' => true,
					),
				),
			),
		);
	}
	
	protected function beforeSave()
	{
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				$this->token = StrHelper::generateRandomString(32);
			}
			return true;
		} else {
			return false;
		}
	}
	
	public function search($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->alias = 'candidate';
		$criteria->compare('candidate.name', $this->name, true);
		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
			'sort' => array(
				'defaultOrder' => 'candidate.name',
			),
		));
	}
	
	public function __toString()
	{
		return $this->name;
	}
	
	public static function getList($params=array())
	{
		$criteria = new CDbCriteria($params);
		$criteria->order = 'name';
		return CHtml::listData(self::model()->findAll($criteria), 'id', 'name');
	}
	
	public static function getListLevels()
	{
		if (null === self::$levels) {
			self::$levels = array(
				self::LEVEL_LOW => Yii::t('candidate', 'Low'),
				self::LEVEL_MODERATE => Yii::t('candidate', 'Moderate'),
				self::LEVEL_HIGH => Yii::t('candidate', 'High'),
				self::LEVEL_TOP => Yii::t('candidate', 'Top'),
			);
		}
		return self::$levels;
	}
	
	public static function getListLocales()
	{
		if (null === self::$locales) {
			self::$locales = array(
				'en' => 'English',
				'ru' => 'Русский',
			);
		}
		return self::$locales;
	}
	
	public function getLevelName()
	{
		return array_key_exists($this->level, self::getListLevels()) ? self::$levels[$this->level] : '';
	}
	
	public function getLocaleName()
	{
		return array_key_exists($this->lang, self::getListLocales()) ? self::$locales[$this->lang] : '';
	}
	
	public function getExamineStarted()
	{
		return $this->time_examine_started
			? Yii::app()->format->formatDatetime($this->time_examine_started)
			: Yii::t('core.crud', 'No');
	}
	
	public function getExamineEnded()
	{
		return $this->time_examine_ended
			? Yii::app()->format->formatDatetime($this->time_examine_ended)
			: Yii::t('core.crud', 'No');
	}
	
	public function getScore()
	{
		$percent = $this->examine_score_max > 0 
			? round(100 * $this->examine_score / $this->examine_score_max)
			: 0; 
		
		return $this->time_examine_ended
			? Yii::t('core.crud', '{n} / {max} ({percent}%)', array(
				'{n}' => (int) $this->examine_score,
				'{max}' => (int) $this->examine_score_max,
				'{percent}' => (int) $percent,
			))
			: Yii::t('core.crud', 'No');
	}
	
	public function getExaminationLink()
	{
		return Yii::app()->createAbsoluteUrl('examine/default/index', array('token' => $this->publicToken));
	}
	
	public function getPublicToken()
	{
		return base64_encode(CJSON::encode(array(
			'id' => $this->id,
			'token' => $this->token,
		)));
	}
	
	public function isExamined()
	{
		return $this->time_examine_ended != null;
	}
	
	public function isExaminationStarted()
	{
		return $this->time_examine_started != null;
	}
	
	public function startExamination()
	{
		$this->prepareQuestions();
		$this->updateByPk($this->id, array(
			'time_examine_started' => new CDbExpression('NOW()'),
		));
	}
	
	protected function getCategoryIds()
	{
		return array_map(function($category) {
			return $category->id;
		}, $this->categories);
	}
	
	protected function prepareQuestions()
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'question';
		$criteria->compare('question.level', $this->level);
		$criteria->addInCondition('question.category_id', $this->getCategoryIds());
		$criteria->order = new CDbExpression('RAND()');
		$criteria->limit = $this->questions_limit;
		$questions = Question::model()->findAll($criteria);
		foreach ($questions as $question) {
			$answer = new CandidateAnswer('create');
			$answer->candidate_id = $this->id;
			$answer->question_id = $question->id;
			$answer->save();
		}
	}
	
	public function getQuestion()
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'candidate_answer';
		$criteria->compare('candidate_answer.candidate_id', $this->id);
		$criteria->addCondition('candidate_answer.time_answered IS NULL');
		$criteria->limit = 1;
		$answer = CandidateAnswer::model()->find($criteria);
		if ($answer !== null) {
			if ($answer->time_questioned == null) {
				$answer->updateByPk($answer->id, array(
					'time_questioned' => new CDbExpression('NOW()'),
				));
				$answer->time_questioned = date('Y-m-d H:i:s');
			}
			$answer->question->time_questioned = $answer->time_questioned;
			return $answer->question;
		}
		return null;
	}
	
	public function setAnswer($questionId, $answerId)
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'candidate_answer';
		$criteria->compare('candidate_answer.candidate_id', $this->id);
		$criteria->compare('candidate_answer.question_id', $questionId);
		$criteria->limit = 1;
		$answer = CandidateAnswer::model()->find($criteria);
		if ($answer !== null && $answer->time_answered == null) {
			$question = $answer->question;
			$choices = $question->getAnswers()->getData();
			foreach ($choices as $choice) {
				if ($choice->id == $answerId) {
					$answer->updateByPk($answer->id, [
						'answer_id' => $choice->id,
						'time_answered' => new CDbExpression('NOW()'),
					]);
					return true;
				}
			}
		}
		return false;
	}
	
	public function endExamination($abandoned = false)
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'candidate_answer';
		$criteria->compare('candidate_answer.candidate_id', $this->id);
		$answers = CandidateAnswer::model()->findAll($criteria);
		$totalScore = 0;
		$totalScoreMax = 0;
		foreach ($answers as $answer) {
			$question = $answer->question;
			$scoreMax = $question->getMaxScore();
			$totalScoreMax += $scoreMax;
			if (!$answer->isExpired() && $answer->answer !== null) {
				$totalScore += $answer->answer->score;
			}
		}
		$this->updateByPk($this->id, array(
			'time_examine_ended' => new CDbExpression('NOW()'),
			'examine_score' => $totalScore,
			'examine_score_max' => $totalScoreMax,
			'abandoned' => $abandoned ? 1 : 0,
		));
	}
	
	public function getExaminationResults()
	{
		$model = new CandidateAnswer('search');
		$model->unsetAttributes();
		$model->candidate_id = $this->id;
		$provider = $model->search();
		$provider->pagination = false;
		return $provider;
	}
	
	public function resetExaminationResults()
	{
		$criteria = new CDbCriteria();
		$criteria->compare('candidate_id', $this->id);
		CandidateAnswer::model()->deleteAll($criteria);
		$this->updateByPk($this->id, array(
			'token' => StrHelper::generateRandomString(32),
			'time_examine_started' => new CDbExpression('NULL'),
			'time_examine_ended' => new CDbExpression('NULL'),
			'examine_score' => new CDbExpression('NULL'),
			'examine_score_max' => new CDbExpression('NULL'),
			'abandoned' => 0,
		));
	}
	
	public function getTotalQuestions()
	{
		return count($this->results);
	} 
	
	public function getAnsweredQuestions()
	{
		return count(array_filter($this->results, function($answer) {
			return $answer->time_answered != null;
		}));
	}
	
	public function getQuestionsAvailable()
	{
		$criteria = new CDbCriteria();
		$criteria->alias = 'question';
		$criteria->compare('question.level', $this->level);
		$criteria->addInCondition('question.category_id', $this->getCategoryIds());
		return Question::model()->count($criteria);
	}
}
