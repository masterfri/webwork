<?php

$this->pageHeading = Yii::t('core.crud', 'Question Updating');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Questions') => Yii::app()->user->checkAccess('view_question') ? array('index') : false, 
	Yii::t('core.crud', 'Update'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Question'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_question'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'View Question'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_question'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Questions'), 
			'class' => 'btn btn-default',
		), 
		'url'=>array('index'),
		'visible' => Yii::app()->user->checkAccess('view_question'),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_form', array(
			'model' => $model,
		)); ?>
	</div>
</div>


<div class="pull-right">
	<?php $this->widget('zii.widgets.CMenu', array(
		'items' => array(
			array(
				'label' => '<i class="glyphicon glyphicon-plus"></i>', 
				'linkOptions' => array(
					'title' => Yii::t('core.crud', 'Add Answer'),
					'class' => 'btn btn-default',
					'data-raise' => 'ajax-modal',
					'data-modal-css-class' => 'answer-form-modal',
				),
				'url' => array('answer/create', 'question' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_question'),
			),
		),
		'encodeLabel' => false,
		'activateItems' => true,
		'htmlOptions' => array(
			'class' => 'nav nav-pills context-menu',
		),
		'submenuHtmlOptions' => array(
			'class' => 'dropdown-menu dropdown-menu-right pull-right',
			'role' => 'menu',
		),
	)); ?>
</div>
<div class="clearfix"></div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('question', 'Answers') ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'answers-grid',
		'dataProvider' => $model->getAnswers(),
		'columns' => array(
			array(
				'header' => '#',
				'value' => '$row + 1',
			),
			'text:excerpt',
			'score',
			array(
				'class' => 'ButtonColumn',
				'updateButtonUrl' => 'Yii::app()->controller->createUrl("answer/update", array("id" => $data->id))',
				'deleteButtonUrl' => 'Yii::app()->controller->createUrl("answer/delete", array("id" => $data->id))',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this answer?'),
				'template' => '{update} {delete}',
				'buttons' => array(
					'update' => array(
						'options' => array(
							'data-raise' => 'ajax-modal',
							'class' => 'btn btn-default btn-sm update',
							'title' => Yii::t('core.crud', 'Update'),
						),
					),
				),
			),
		),
	)); ?>
</div>

<?php

Yii::app()->clientScript->registerScript('ajax', "
$.ajaxBindings.on('answer.created answer.updated', function() {
	$.fn.yiiGridView.update('answers-grid');
});
$.ajaxBindings.on('answerform.loaded', function() {
	$('#Answer_text').markdown({resize: 'vertical'});
});
");

Yii::app()->clientScript->registerCss('modal-width', '.answer-form-modal .modal-dialog {width: 850px !important;}');