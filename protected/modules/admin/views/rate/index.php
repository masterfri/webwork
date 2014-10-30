<?php

$this->pageHeading = Yii::t('admin.crud', 'Manage Rate');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Rate'), 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Create Rate'), 
			'class' => 'btn btn-default',
		),
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_rate'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-search"></i>', 
		'url' => '#',
		'linkOptions' => array(
			'title' => Yii::t('admin.crud', 'Search'), 
			'class' => 'search-button btn btn-default',
			'data-toggle' => 'search-form',
		),
	),
);

?>

<div class="panel panel-default search-form" style="display: none;">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('admin.crud', 'Search'); ?></h3>
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
		'id' => 'rate-grid',
		'dataProvider' => $provider,
		'columns' => array(
			array('class' => 'LinkColumn', 'name' => 'name'),
			'power:number',
			array(
				'class' => 'ButtonColumn',
				'deleteConfirmation' => Yii::t('admin.crud', 'Are you sure you want to delete this rate?'),
				'template' => '{view}'.
					(Yii::app()->user->checkAccess('update_rate') ? ' {update}' : '').
					(Yii::app()->user->checkAccess('delete_rate') ? ' {delete}' : ''),
			),
		),
	)); ?>
</div>
