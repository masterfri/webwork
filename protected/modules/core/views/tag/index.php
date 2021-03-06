<?php

$this->pageHeading = Yii::t('core.crud', 'Manage Tags');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Tags'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Tag'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_tag'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-search"></i>', 
		'url' => '#',
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Search'), 
			'class' => 'search-button btn btn-default',
			'data-toggle' => 'search-form',
		),
	),
);

?>

<div class="panel panel-default search-form" style="display: none;">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('core.crud', 'Search'); ?></h3>
	</div>
	<div class="panel-body">
		<?php $this->renderPartial('_search',array(
			'model' => $model,
		)); ?>
	</div>
</div>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<?php $this->widget('GridView', array(
		'id' => 'tag-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array('class' => 'LinkColumn', 'name' => 'name'),
			array(
				'class' => 'LinkColumn', 
				'name' => 'project',
				'linkExpression' => 'array("project/view", "id" => $data->project_id)',
				'activityExpression' => 'Yii::app()->user->checkAccess("view_project", array("project" => $data->project))',
			),
			array('name' => 'color', 'type' => 'raw', 'value' => "sprintf('<div style=\"width: 20px; height: 20px; background: %s; border: 1px solid black; \"></div>', \$data->color)"),
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('core.crud', 'Are you sure you want to delete this tag?'),
				'template' => '{view} {update} {delete}',
				'buttons' => array(
					'update' => array(
						'visible' => 'Yii::app()->user->checkAccess("update_tag", array("tag" => $data))',
					),
					'delete' => array(
						'visible' => 'Yii::app()->user->checkAccess("delete_tag", array("tag" => $data))',
					),
				),
			),
		),
	)); ?>
</div>
