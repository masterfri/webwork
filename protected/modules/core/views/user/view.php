<?php

$this->pageHeading = Yii::t('core.crud', 'User Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Users') => Yii::app()->user->checkAccess('view_user') ? array('index') : false, 
	Yii::t('core.crud', 'User Information')
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create User'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_user'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Users'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_user'),
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
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update User'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_user'),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete User'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this user?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_user'),
			),
		),
	),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo CHtml::encode($model->displayName); ?></h3>
	</div>
	<?php $this->widget('DetailView', array(
		'data' => $model,
		'attributes' => array(
			'real_name',
			'username',
			'email',
			'roleName',
			'rate',
			array(
				'name' => 'working_hours',
				'value' => CHtml::value($model, "working_hours", Yii::t("workingHours", "General")),
			),
			'statusName',
			'localeName',
			'documentLocaleName',
		),
	)); ?>
</div>
<?php if ($model->legal_type): ?>
	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo Yii::t('user', 'Legal Entity'); ?></h3>
		</div>
		<?php $this->widget('DetailView', array(
			'data' => $model,
			'attributes' => array(
				'legal_name',
				'legalTypeName',
				'legal_signer_name',
				'legal_number',
				'legal_address',
			),
		)); ?>
	</div>
<?php endif; ?>

<div class="row">
	<div class="col-sm-4">
		<?php $this->renderPartial('_working_hours', array(
			'model' => $model,
		)); ?>
		<?php $this->renderPartial('_working_days_col', array(
			'model' => $model,
			'monthnum' => $monthnum,
			'month1' => $month1,
			'month2' => $month2,
			'month1i' => $month1i,
			'month2i' => $month2i,
			'month1days' => $month1days,
			'month2days' => $month2days,
		)); ?>
	</div>
	
	<div class="col-sm-8">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo Yii::t('user', 'Activity') ?></h3>
			</div>
			<div class="panel-body">
				<canvas id="activity_chart" style="width: 100%;" height="100"></canvas>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo Yii::t('user', 'Current tasks') ?></h3>
			</div>
			<div class="panel-body">
				<?php $this->widget('TaskListView', array(
					'id' => 'task-grid',
					'dataProvider' => $currentTasks,
					'group_by_date' => true,
					'template' => '{items} {pager}',
				)); ?>
			</div>
		</div>
	</div>
</div>

<?php 

$activitiy_title = CJSON::encode(Yii::t('user', 'Activity (hrs)'));
$activitiy = $model->getActivityLevel(31);
$activitiy_values = CJSON::encode(array_values($activitiy));
$activitiy_labels = CJSON::encode(array_map(function($v) {return date('d/m', strtotime($v));}, array_keys($activitiy)));
$activitiy_ticks = max(1, ceil(max($activitiy) / 10));
Yii::app()->clientScript->registerScriptFile('/rc/js/Chart.min.js');
Yii::app()->clientScript->registerScript('charts',
<<<EOS
$(function() {
	new Chart(document.getElementById("activity_chart").getContext("2d"), {
		'type': 'line',
		'data': {
			'labels': $activitiy_labels,
			'datasets': [{
				'label': $activitiy_title,
				'borderColor': 'rgba(92,148,92, 1)',
				'pointColor': 'rgba(92,148,92, 1)',
				'backgroundColor': 'rgba(92,148,92, 0.8)',
				'data': $activitiy_values,
				'pointStyle': 'line'
			}]
		},
		'options': {
			'elements': {
				'line': {
					'tension': 0
				}
			},
			'legend': {
				'display': false
			},
			'scales': {
				'yAxes': [{
					'ticks': {
						'stepSize': $activitiy_ticks,
						'min': 0
					}
				}],
				'xAxes': [{
					'ticks': {
						'autoSkip': false
					}
				}]
			}
		}
	});
});
EOS
);