<?php

$this->pageHeading = Yii::t('core.crud', 'Milestone Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('core.crud', 'Milestones') => Yii::app()->user->checkAccess('view_milestone', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Milestone'),
			'class' => 'btn btn-default',
		),
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_milestone', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-tasks"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Tasks'),
			'class' => 'btn btn-default',
		),
		'url' => array('task/index', 'project' => $model->project->id, 'milestone' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_task', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Milestones'),
			'class' => 'btn btn-default',
		),
		'url' => array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_milestone', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back to Project'),
			'class' => 'btn btn-default',
		),
		'url' => array('project/view', 'id' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)),
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
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update Milestone'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_milestone', array('milestone' => $model)),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete Milestone'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this milestone?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_milestone', array('milestone' => $model)),
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
			'date_start:date',
			'due_date:date',
		),
	)); ?>
	<div class="panel-body">
		<?php if ('' != $model->description): ?>
			<?php 
				$this->beginWidget('MarkdownWidget', array('attachments' => $model->attachments)); 
				echo $model->description;
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
		<?php echo Yii::t('milestone', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>
