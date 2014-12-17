<?php

/**
 * This class owerride default `user` component
 */ 

class WebUser extends CWebUser
{
	public $guestRole = 'guest';
	protected $_model;
	protected $_locale;
	
	public function getRole()
	{
		if (! $this->isGuest) {
			$model = $this->getModel();
			if ($model) {
				return $model->role;
			}
		}
		return $this->guestRole;
	}
	
	public function getLocale()
	{
		if (null === $this->_locale) {
			$locale = (string) Yii::app()->request->cookies['locale'];
			if ('' === $locale) {
				$locale = 'en';
				$model = $this->getModel();
				if ($model) {
					$locale = $model->locale;
				}
			}
			$this->setLocale($locale);
		}
		return $this->_locale;
	}
	
	public function setLocale($locale)
	{
		$this->_locale = $locale;
		$cookie = new CHttpCookie('locale', $locale);
		$cookie->expire = time() + 31104000;
		Yii::app()->request->cookies['locale'] = $cookie;
	}
	
	public function isAdmin()
	{
		return 'admin' == $this->getRole();
	}
	
	public function getModel()
	{
		if (null === $this->_model) {
			$this->_model = false;
			if (! $this->isGuest) {
				if ($model = User::model()->findByPk($this->id)) {
					$this->_model = $model;
				}
			}
		}
		return $this->_model;
	}
}
