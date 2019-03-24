<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Question Categories');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Question Categories'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Questions'), 
			'class' => 'btn btn-default',
		),
		'url' => array('question/index'),
		'visible' => Yii::app()->user->checkAccess('view_question'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>',
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Question Category'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_question_category'),
	),
);

?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'question-category-grid',
		'dataProvider' => $provider,
		'columns' => array(
			'name',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this question category?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('update_question_category') ? ' {update}' : '').
					(Yii::app()->user->checkAccess('delete_question_category') ? ' {delete}' : ''),
			),
		),
	)); ?>
</div>
