<?php

Yii::import('zii.widgets.grid.CButtonColumn');

class ButtonColumn extends CButtonColumn
{
	public $viewButtonImageUrl = false;
	public $viewButtonLabel = '<i class="glyphicon glyphicon-eye-open"></i>';
	public $viewButtonOptions = array('title' => 'View', 'class' => 'btn btn-default btn-sm view');
	
	public $updateButtonImageUrl = false;
	public $updateButtonLabel = '<i class="glyphicon glyphicon-pencil"></i>';
	public $updateButtonOptions = array('title' => 'Update', 'class' => 'btn btn-default btn-sm update');
	
	public $deleteButtonImageUrl = false;
	public $deleteButtonLabel = '<i class="glyphicon glyphicon-trash"></i>';
	public $deleteButtonOptions = array('title' => 'Delete', 'class' => 'btn btn-default btn-sm delete');
}
