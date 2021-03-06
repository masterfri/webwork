<?php

$this->pageHeading = Yii::t('core.crud', 'Question Category Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Question Categories') => Yii::app()->user->checkAccess('view_question_category') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Question Category'), 
			'class' => 'btn btn-default',
		),
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_question_category'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Question Categories'), 
			'class' => 'btn btn-default',
		),
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_question_category'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-cog"></i> <span class="caret"></span>', 
		'linkOptions' => array(
			'class' => 'btn btn-default dropdown-toggle',
			'data-toggle' => 'dropdown',
		),
		'itemOptions' => array(
			'class' => 'dropdown',
		),
		'items' => array(
			array(
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update Question Category'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_question_category'),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete Question Category'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this question category?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_question_category'),
			),
		),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo CHtml::encode($model->name); ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,
		'attributes' => array(
			'name',
		),
	)); ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('questionCategory', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>
