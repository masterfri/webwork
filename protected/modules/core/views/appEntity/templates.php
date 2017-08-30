<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Templates');
		
$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	Yii::t('core.crud', 'Templates'), 
);

?>


<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'activity-grid',
		'dataProvider' => $provider,
		'columns' => array(
			'label',
			'description',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this template?'),
				'template' => '{view} {update} {delete}',
				'buttons' => array(
					'update' => array(
						'url' => 'Yii::app()->controller->createUrl("updateTemplate", array("id" => $data->id))',
						'visible' => 'Yii::app()->user->checkAccess("update_entity_template", array("template" => $data))',
						'options' => array(
							'class' => 'btn btn-default btn-sm update',
							'title' => Yii::t('core.crud', 'Update'),
						),
					),
					'delete' => array(
						'url' => 'Yii::app()->controller->createUrl("deleteTemplate", array("id" => $data->id))',
						'visible' => 'Yii::app()->user->checkAccess("delete_entity_template", array("template" => $data))',
					),
				),
			),
		),
	)); ?>
</div>
