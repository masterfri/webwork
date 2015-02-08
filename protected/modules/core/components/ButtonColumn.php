<?php

Yii::import('zii.widgets.grid.CButtonColumn');

class ButtonColumn extends CButtonColumn
{
	public $viewButtonImageUrl = false;
	public $viewButtonLabel = '<i class="glyphicon glyphicon-eye-open"></i>';
	public $viewButtonOptions = null;
	
	public $updateButtonImageUrl = false;
	public $updateButtonLabel = '<i class="glyphicon glyphicon-pencil"></i>';
	public $updateButtonOptions = null;
	
	public $deleteButtonImageUrl = false;
	public $deleteButtonLabel = '<i class="glyphicon glyphicon-trash"></i>';
	public $deleteButtonOptions = null;
	
	protected function initDefaultButtons()
	{
		if (null === $this->viewButtonOptions) {
			$this->viewButtonOptions = array(
				'title' => Yii::t('core.crud', 'View'),
				'class' => 'btn btn-default btn-sm view',
			);
		}
		if (null === $this->updateButtonOptions) {
			$this->updateButtonOptions = array(
				'title' => Yii::t('core.crud', 'Update'),
				'class' => 'btn btn-default btn-sm update',
			);
		}
		if (null === $this->deleteButtonOptions) {
			$this->deleteButtonOptions = array(
				'title' => Yii::t('core.crud', 'Delete'),
				'class' => 'btn btn-default btn-sm delete',
			);
		}
		parent::initDefaultButtons();
	}
}
