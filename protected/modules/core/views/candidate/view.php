<?php

$this->pageHeading = Yii::t('core.crud', 'Candidate Information');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Candidates') => Yii::app()->user->checkAccess('view_candidate') ? array('index') : false, 
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Candidate'), 
			'class' => 'btn btn-default',
		),
		'url' => array('create'),
		'visible' => Yii::app()->user->checkAccess('create_candidate'),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Candidates'), 
			'class' => 'btn btn-default',
		),
		'url' => array('index'),
		'visible' => Yii::app()->user->checkAccess('view_candidate'),
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
				'label' => '<i class="glyphicon glyphicon-pencil"></i> ' . Yii::t('core.crud', 'Update Candidate'), 
				'url' => array('update', 'id' => $model->id),
				'visible' => Yii::app()->user->checkAccess('update_candidate'),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-repeat"></i> ' . Yii::t('core.crud', 'Reset Results'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('reset', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to reset examination results for this candidate?'),
				),
				'visible' => $model->isExamined() && Yii::app()->user->checkAccess('update_candidate'),
			),
			array(
				'label' => '<i class="glyphicon glyphicon-trash"></i> ' . Yii::t('core.crud', 'Delete Candidate'), 
				'url' => '#', 
				'linkOptions' => array(
					'submit' => array('delete', 'id' => $model->id),
					'confirm' => Yii::t('core.crud', 'Are you sure you want to delete this candidate?'),
				),
				'visible' => Yii::app()->user->checkAccess('delete_candidate'),
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
			'name',
			'notes:ntext',
			'levelName',
			'questions_limit',
			'categories:array',
			'examineStarted',
			'examineEnded',
			'score',
			'abandoned:boolean',
		),
	)); ?>
	<div class="panel-footer foot-details">
		<?php echo Yii::t('candidate', 'Created by'); ?>
		<?php echo CHtml::encode($model->created_by); ?>,
		<?php echo Yii::app()->format->formatDatetime($model->time_created); ?>
	</div>
</div>

<?php if (!$model->isExamined()): ?>

	<div class="panel panel-default">
		<div class="panel-heading">
			<h3 class="panel-title"><?php echo Yii::t('candidate', 'Examination Link') ?></h3>
		</div>
		<div class="panel-body">
			<?php echo CHtml::textField(false, $model->examinationLink, array('class' => 'form-control', 'onfocus' => 'this.select()')); ?>
		</div>
	</div>

<?php else: ?>

	<h3><?php echo Yii::t('candidate', 'Examination Results') ?></h3>

	<?php $this->widget('ListView', array(
		'id' => 'answers-list',
		'dataProvider' => $model->getExaminationResults(),
		'itemView' => '_result',
	)); ?>

<?php endif ?>