<?php

$this->pageHeading = Yii::t('admin.crud', 'Rate Information');

$this->breadcrumbs = array(
	Yii::t('admin.crud', 'Rate') => Yii::app()->user->checkAccess('view_rate') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i> ' . Yii::t('admin.crud', 'Create Rate'), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_rate'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('admin.crud', 'Update Rate'), 
		'url' => array('update', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('update_rate'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('admin.crud', 'Delete Rate'), 
		'url' => '#', 
		'linkOptions' => array(
			'submit' => array('delete', 'id' => $model->id),
			'confirm' => Yii::t('admin.crud', 'Are you sure you want to delete this rate?'),
		),
		'visible' => Yii::app()->user->checkAccess('delete_rate'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-wrench"></i> ' . Yii::t('admin.crud', 'Manage Rate'), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_rate'),
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
			'description:ntext',
			'power',
			'time_created:datetime',
			'created_by',
		),
	)); ?>
</div>

<?php if (count($rates = $model->getCompleteMatrix())): ?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo Yii::t('activityRate', 'Hour Rates'); ?></h3>
	</div>
	
	<table class="table table-striped table-bordered table-condensed detailed-view">
		<tbody>
			<?php foreach ($rates as $rate): ?>
				<tr>
					<th><?php echo CHtml::encode($rate->activity->name); ?></th>
					<td><?php echo CHtml::encode($rate->hour_rate); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php endif; ?>
