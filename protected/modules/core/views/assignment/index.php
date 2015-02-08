<?php

$this->pageHeading = Yii::t('core.crud', 'Team');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $project)) ? array('project/view', 'id' => $project->id) : false, 
	Yii::t('core.crud', 'Team'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Add a Member'), 
			'class' => 'btn btn-default',
			'data-raise' => 'ajax-modal',
		),
		'url' => array('create', 'project' => $project->id),
		'visible' => Yii::app()->user->checkAccess('create_assignment', array('project' => $project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i> ', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back to Project'), 
			'class' => 'btn btn-default',
		),
		'url' => array('project/view', 'id' => $project->id),
		'visible' => Yii::app()->user->checkAccess('view_project', array('project' => $project)),
	),
);

?>


<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'assignment-grid',
		'dataProvider' => $provider,
		'columns' => array(
			'user',
			array('name' => 'role', 'value' => '$data->getRoleName()'),
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this member?'),
				'template' => '{update} {delete}',
				'buttons' => array(
					'update' => array(
						'visible' => 'Yii::app()->user->checkAccess("update_assignment", array("assignment" => $data))',
						'options' => array(
							'data-raise' => 'ajax-modal',
							'class' => 'btn btn-default btn-sm update',
							'title' => Yii::t('core.crud', 'Update'),
						),
					),
					'delete' => array(
						'visible' => 'Yii::app()->user->checkAccess("delete_assignment", array("assignment" => $data))',
					),
				),
			),
		),
	)); ?>
</div>

<?php

Yii::app()->clientScript->registerScript('ajax', "
$.ajaxBindings.on('assignment.created assignment.updated', function() {
	$.fn.yiiGridView.update('assignment-grid');
});
");
