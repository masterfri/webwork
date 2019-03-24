<?php

$this->pageHeading = Yii::t('core.crud', 'New Answer');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Answer') => Yii::app()->user->checkAccess('update_question') ? array('question/update', 'id' => $model->question_id) : false, 
	Yii::t('core.crud', 'Create'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back to Question'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('question/update', 'id' => $model->question_id),
		'visible' => Yii::app()->user->checkAccess('update_question'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title" data-marker="ajax-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div data-marker="ajax-body">
			<?php $this->renderPartial('_form', array(
				'model' => $model,
			)); ?>
		</div>
	</div>
</div>
