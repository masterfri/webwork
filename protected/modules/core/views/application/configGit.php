<?php

$this->pageHeading = Yii::t('core.crud', 'Configure Git');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($model->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $model->project)) ? array('project/view', 'id' => $model->project->id) : false, 
	Yii::t('core.crud', 'Applications') => Yii::app()->user->checkAccess('view_application', array('project' => $model->project)) ? array('index', 'project' => $model->project->id) : false, 
	Yii::t('core.crud', 'Configure Git'),
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-plus"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Create Application'),
			'class' => 'btn btn-default',
		),
		'url' => array('create', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('create_application', array('project' => $model->project)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-eye-open"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'View Application'),
			'class' => 'btn btn-default',
		),
		'url' => array('view', 'id' => $model->id),
		'visible' => Yii::app()->user->checkAccess('view_application', array('application' => $model)),
	),
	array(
		'label' => '<i class="glyphicon glyphicon-list-alt"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Manage Applications'),
			'class' => 'btn btn-default',
		),
		'url'=>array('index', 'project' => $model->project->id),
		'visible' => Yii::app()->user->checkAccess('view_application', array('project' => $model->project)),
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
);

if (null !== $response && !$response->getIsSuccess()) {
	$this->renderPartial('../layouts/include/httpsh-response', array(
		'response' => $response,
	));
}

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<div class="form-content">
			<?php $form=$this->beginWidget('ActiveForm', array(
				'id' => 'application-form',
				'htmlOptions' => array(
					'class'=>'form-horizontal',
				),
				'enableClientValidation' => true,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'afterValidate' => 'js:function(f,d,e) {
						if (e) $("html, body").animate({scrollTop: $("#application-form").offset().top - 50}, 1000);
						return true;
					}',
				),
			)); ?>
			
			<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
			
			<?php if(!($model->status & Application::STATUS_HAS_GIT)): ?>
				<div class="form-group">
					<?php echo $form->labelEx($model, 'create_repo', array('class'=>'col-sm-3 control-label')); ?>
					<div class="col-sm-9">
						<div class="checkbox">
							<?php echo $form->checkbox($model, 'create_repo', array(
								'onchange' => "if (this.checked) $('#giturl').hide(); else $('#giturl').show();",
							)); ?> 
						</div>
						<?php echo $form->error($model, 'create_repo', array('class'=>'help-inline')); ?>
					</div>
				</div>
			<?php endif; ?>
			<div class="form-group" style="display: <?php echo $model->create_repo == 1 ? 'none' : 'block'; ?>;" id="giturl">
				<?php echo $form->labelEx($model, 'git', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-9">
					<?php echo $form->textField($model, 'git', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'git', array('class'=>'help-inline')); ?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->labelEx($model, 'git_branch', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-9">
					<?php echo $form->textField($model, 'git_branch', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'git_branch', array('class'=>'help-inline')); ?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<?php if ($wizard): ?>
						<?php echo CHtml::submitButton(Yii::t('core.crud', 'Next Step'), array('class'=>'btn btn-primary')); ?>
						<?php echo CHtml::link(Yii::t('core.crud', 'Skip this Step'), array('configDb', 'id' => $model->id, 'wizard' => 1), array('class'=>'btn btn-default')); ?>
					<?php else: ?>
						<?php echo CHtml::submitButton(Yii::t('core.crud', 'Update'), array('class'=>'btn btn-primary')); ?>
					<?php endif; ?>
				</div>
			</div>

			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
