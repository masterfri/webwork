<?php

$this->pageHeading = Yii::t('core.crud', 'Project Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Project'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_project'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-calendar"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Milestones'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('milestone/index', 'project' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_milestone', array('project' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-tasks"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Tasks'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('task/index', 'project' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('project' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-th"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Schedule'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('schedule/index', 'project' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_schedule', array('project' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-globe"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Applications'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('application/index', 'project' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_application', array('project' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Projects'), 
			'class' => 'btn btn-default',
		), 
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_project'),
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
				'label' => '<i class="glyphicon glyphicon-file"></i> ' . Yii::t('core.crud', 'Specification'), 
				'url' => array('pdf', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('view_project', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-user"></i> ' . Yii::t('core.crud', 'Team'), 
				'url' => array('assignment/index', 'project' => $model->id),
				'visible' => Yii::app()->user->checkAccess('view_assignment', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update Project'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_project', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-save"></i> ' . Yii::t('core.crud', 'Archive Project'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('archive', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to archive this project?'),
				),
				'visible' => $model->archived == 0 && Yii::app()->user->checkAccess('archive_project', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-open"></i> ' . Yii::t('core.crud', 'Activate Project'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('activate', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to activate this project?'),
				),
				'visible' => $model->archived == 1 && Yii::app()->user->checkAccess('activate_project', array('project' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete Project'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this project?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_project', array('project' => $model)),
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
			'assignments:array',
			'bonusValue',
		),
	)); ?>
	<div class="panel-body">
		<?php if ('' != $model->scope): ?>
			<?php 
				$this->beginWidget('MarkdownWidget'); 
				echo $model->scope;
				$this->endWidget(); 
			?>
		<?php else: ?>
			<p class="not-set"><?php echo Yii::t('core.crud', 'No description given'); ?></p>
		<?php endif; ?>
	</div>
	<?php if (count($model->attachments)): ?>
		<div class="panel-footer attachments">
			<?php foreach ($model->attachments as $attachment): ?>
				<a class="thumbnail" target="_blank" href="<?php echo $attachment->getUrl(); ?>">
					<?php if ($attachment->getIsImage()): ?>
						<?php echo CHtml::image($attachment->getUrlResized(150, 100), '', array('title' => $attachment->title)); ?>
					<?php else: ?>
						<span class="no-thumb">
							<span class="file-name">
								<?php echo CHtml::encode($attachment->title); ?>
							</span>
							<span class="file-type">
								<?php echo CHtml::encode($attachment->mime); ?>
							</span>
							<span class="file-size">
								<?php echo $attachment->getFriendlySize(); ?>
							</span>
						</span>
					<?php endif; ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('project', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->date_created); ?>
	</div>
</div>

<?php if(Yii::app()->user->checkAccess('view_project_stats', array('project' => $model))): ?>
<div class="row">
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo Yii::t('project', 'Activity') ?></h3>
			</div>
			<div class="panel-body">
				<canvas id="activity_chart" style="width: 100%; height: 200px;"></canvas>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo Yii::t('project', 'Load Balance') ?></h3>
			</div>
			<div class="panel-body">
				<canvas id="balance_chart" style="width: 100%; height: 200px;"></canvas>
			</div>
		</div>
	</div>
</div>

<?php 

$activitiy_title = CJSON::encode(Yii::t('project', 'Activity (hrs)'));
$activitiy = $model->getActivityLevel(31);
$activitiy_values = CJSON::encode(array_values($activitiy));
$activitiy_labels = CJSON::encode(array_map(function($v) {return date('d/m', strtotime($v));}, array_keys($activitiy)));
$activitiy_ticks = max(1, ceil(max($activitiy) / 10));
$balance_title_open = CJSON::encode(Yii::t('project', 'Opened'));
$balance_title_close = CJSON::encode(Yii::t('project', 'Closed'));
$balance = $model->getBalance(31);
$balance_open = CJSON::encode(array_values(array_map(function($v) {return $v[0];}, $balance)));
$balance_close = CJSON::encode(array_values(array_map(function($v) {return $v[1];}, $balance)));
$balance_labels = CJSON::encode(array_map(function($v) {return date('d/m', strtotime($v));}, array_keys($balance)));
$balance_ticks = max(1, ceil(max(array_map(function($v) {return max($v[0], $v[1]);}, $balance)) / 10));
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
	
	new Chart(document.getElementById("balance_chart").getContext("2d"), {
		'type': 'bar',
		'data': {
			'labels': $balance_labels,
			'datasets': [{
				'label': $balance_title_open,
				'backgroundColor': 'rgba(217,83,79, 0.8)',
				'data': $balance_open
			}, {
				'label': $balance_title_close,
				'backgroundColor': 'rgba(92,148,92, 0.8)',
				'data': $balance_close
			}]
		},
		'options': {
			'legend': {
				'display': false
			},
			'scales': {
				'yAxes': [{
					'ticks': {
						'stepSize': $balance_ticks,
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

endif; ?>

<?php if (false !== $notes): ?>
	<div class="project-notes">
		<div class="row">
			<div class="col-sm-8">
				<h3><?php echo Yii::t('core.crud', 'Notes'); ?></h3>
			</div>
			<div class="col-sm-4">
				<div class="pull-right">
					<?php $this->widget('zii.widgets.CMenu', array(
						'items' => array(
							array(
								'label' => '<i class="glyphicon glyphicon-plus"></i>', 
								'linkOptions' => array(
									'title' => Yii::t('core.crud', 'Create Note'), 
									'class' => 'btn btn-default',
									'data-raise' => 'ajax-modal',
								), 
								'url' => array('note/create', 'project' => $model->id),
								'visible' => Yii::app()->user->checkAccess('create_note', array('project' => $model)),
							),
						),
						'encodeLabel' => false,
						'htmlOptions' => array(
							'class' => 'nav nav-pills context-menu',
						),
					)); ?>
				</div>
			</div>
		</div>
		<div class="row">
			<?php $this->widget('ListView', array(
				'id' => 'notes-list',
				'dataProvider' => $notes,
				'itemView' => '../note/view',
				'emptyText' => Yii::app()->user->checkAccess('create_note', array('project' => $model)) ?
					('<div class="col-xs-12"><div class="create-first-item"><a href="' . $this->createUrl('note/create', array('project' => $model->id)) . '" data-raise="ajax-modal">' . Yii::t('core.crud', 'Create first note') . '</a></div></div>') : 
					('<div class="col-xs-12"><span class="empty">' . Yii::t('core.crud', 'There are no notes yet') . '</span></div>'),
			)); ?>
		</div>
	</div>
<?php

Yii::app()->clientScript->registerScript('notes',
<<<EOS
$.ajaxBindings.on('note.created note.updated note.deleted', function() {
	$.fn.yiiListView.update('notes-list');
});
EOS
);

endif; ?>
