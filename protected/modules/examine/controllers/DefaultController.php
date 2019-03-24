<?php

class DefaultController extends CController
{
	public $layout = 'examine.views.layouts.default';
	protected $candidate;
	protected $showHeaderContent = false;

	public function actionIndex($token)
	{
		$candidate = $this->loadCandidate($token);
		
		if ($candidate->isExamined()) {
			$this->redirect($this->createTokenUrl('done'));
		}
		
		$this->render('index', array(
			'candidate' => $candidate,
		));
	}
	
	public function actionStart($token)
	{
		$candidate = $this->loadCandidate($token);
		
		if (!$candidate->isExaminationStarted()) {
			$candidate->startExamination();
		}
		
		$this->redirect($this->createTokenUrl('question'));
	}
	
	public function actionQuestion($token)
	{
		$candidate = $this->loadCandidate($token);
		
		if ($candidate->isExamined()) {
			$this->redirect($this->createTokenUrl('done'));
		}
		
		if (!$candidate->isExaminationStarted()) {
			$candidate->startExamination();
		}
		
		$question = $candidate->getQuestion();
		if (!$question) {
			$candidate->endExamination();
			$this->redirect($this->createTokenUrl('done'));
		}
		
		if ($this->isAjax()) {
			$this->layout = 'ajax';
		} else {
			$this->showHeaderContent = true;
		}
		
		$this->render('question', array(
			'candidate' => $candidate,
			'question' => $question,
			'number' => $candidate->getAnsweredQuestions() + 1,
			'total' => $candidate->getTotalQuestions(),
		));
	}
	
	public function actionAnswer($token, $question)
	{
		$candidate = $this->loadCandidate($token);
		
		if ($candidate->isExamined()) {
			$this->redirect($this->createTokenUrl('done'));
		}
		
		if (!$candidate->isExaminationStarted()) {
			$this->redirect($this->createTokenUrl('index'));
		}
		
		$answer = isset($_POST['answer']) ? $_POST['answer'] : null;
		$candidate->setAnswer($question, $answer);
		
		$this->redirect($this->createTokenUrl('question'));
	}
	
	public function actionGiveup($token)
	{
		$candidate = $this->loadCandidate($token);
		
		if ($candidate->isExamined()) {
			$this->redirect($this->createTokenUrl('done'));
		}
		
		$candidate->endExamination(true);
		
		$this->redirect($this->createTokenUrl('done'));
	}
	
	public function actionDone($token)
	{
		$candidate = $this->loadCandidate($token);
		
		if ($this->isAjax()) {
			$this->layout = 'ajax';
		}
		
		$this->render('done', array(
			'candidate' => $candidate,
		));
	}
	
	public function actionError()
	{
		if($error = Yii::app()->errorHandler->error) {
			if(Yii::app()->request->isAjaxRequest) {
				echo $error['message'];
			} else {
				$this->layout = 'error';
				$this->render('error', $error);
			}
		}
	}
	
	protected function loadCandidate($token)
	{
		$tokenData = @CJSON::decode(base64_decode($token));
		$model = Candidate::model()->findByPk($tokenData['id']);
		if (!$model || $model->token != $tokenData['token']) {
			throw new CHttpException(404, Yii::t('site', 'Not found'));
		}
		$this->candidate = $model;
		return $model;
	}
	
	protected function createTokenUrl($path, $args = array())
	{
		return $this->createUrl($path, array_merge($args, array(
			'token' => $this->candidate->publicToken,
		)));
	}
	
	public function isAjax()
	{
		return Yii::app()->request->isAjaxRequest;
	}
}