<?php

$this->pageHeading = Yii::t('core.crud', 'Pushing to Repository');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($application->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $application->project)) ? array('project/view', 'id' => $application->project->id) : false, 
	CHtml::encode($application->name) => Yii::app()->user->checkAccess('view_application', array('application' => $application)) ? array('application/view', 'id' => $application->id) : false, 
	Yii::t('core.crud', 'Push')
);

$this->menu = array(
	array(
		'label' => '<i class="glyphicon glyphicon-arrow-left"></i>', 
		'linkOptions' => array(
			'title' => Yii::t('core.crud', 'Back'), 
			'class' => 'btn btn-default',
		),
		'url' => array('index', 'application' => $application->id),
		'visible' => Yii::app()->user->checkAccess('design_application', array('application' => $application)),
	)
);

?>

<?php if ($response !== null): ?> 
	<?php $this->renderPartial('../layouts/include/httpsh-response', array(
		'response' => $response,
	)); ?>
<?php endif; ?>

<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<?php $form=$this->beginWidget('ActiveForm', array(
				'id' => 'push-form',
				'htmlOptions' => array(
					'class'=>'form-horizontal',
				),
				'enableClientValidation' => true,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'afterValidate' => 'js:function(f,d,e) {
						if (e) {
							$("html, body").animate({scrollTop: $("#push-form").offset().top - 50}, 1000);
						} else {
							f.children(".preloader").show();
							f.children(".options").hide();
							setTimeout(function() { f.find(".btn").attr("disabled", "disabled"); }, 50);
						}
						return true;
					}',
				),
			)); ?>
			
			<?php if ($model->hasConflictedFiles()): ?>
				<div class="alert alert-warning"><?php echo Yii::t('core.crud', 'There are files modified by others.'); ?></div>
			<?php endif;?>
			
			<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
			<input name="push" type="hidden" value="1" />
			<div class="options">
				<div class="form-group">
					<div class="col-sm-12">
						<?php if ($model->hasConflictedFiles()): ?>
							<table id="conflicts-table" class="table table-bordered table-striped table-condensed">
								<thead>
									<tr>
										<th width="1"><a href="#" data-val="<?php echo PushApplicationForm::RESOLVE_IGNORE; ?>"><?php echo Yii::t('core.crud', 'Ignore'); ?></a></th>
										<th width="1"><a href="#" data-val="<?php echo PushApplicationForm::RESOLVE_OVERWRITE; ?>"><?php echo Yii::t('core.crud', 'Overwrite'); ?></a></th>
										<th><?php echo Yii::t('core.crud', 'File'); ?></th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ($model->resolves as $file => $resolution): ?>
										<tr>
											<td align="center">
												<?php echo CHtml::radioButton(sprintf('PushApplicationForm[resolves][%s]', $file), $resolution == PushApplicationForm::RESOLVE_IGNORE, array(
													'value' => PushApplicationForm::RESOLVE_IGNORE,
												)); ?>
											</td>
											<td align="center">
												<?php echo CHtml::radioButton(sprintf('PushApplicationForm[resolves][%s]', $file), $resolution == PushApplicationForm::RESOLVE_OVERWRITE, array(
													'value' => PushApplicationForm::RESOLVE_OVERWRITE,
												)); ?>
											</td>
											<td><?php echo CHtml::encode($file); ?></td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						<?php endif; ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo $form->labelEx($model, 'branch', array('class'=>'col-sm-3 control-label')); ?>
					<div class="col-sm-9">
						<?php echo $form->textField($model, 'branch', array(
							'class' => 'form-control',
						)); ?> 
						<?php echo $form->error($model, 'branch', array('class'=>'help-inline')); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo $form->labelEx($model, 'message', array('class'=>'col-sm-3 control-label')); ?>
					<div class="col-sm-9">
						<?php echo $form->textArea($model, 'message', array(
							'class' => 'form-control',
						)); ?> 
						<?php echo $form->error($model, 'message', array('class'=>'help-inline')); ?>
					</div>
				</div>
			</div>
			<div style="display: none;" class="preloader">
				<p><?php echo Yii::t('core.crud', 'Operation in process... Please, be patient.'); ?></p>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<?php echo CHtml::submitButton(Yii::t('core.crud', 'Push'), array('class'=>'btn btn-primary')); ?>
				</div>
			</div>
		<?php $this->endWidget(); ?>
	</div>
</div>

<?php

Yii::app()->clientScript->registerScript('toggleall',
<<<ENDJS
var conflicts = $('#conflicts-table');
conflicts.length > 0 && (function() {
	conflicts.on('click', 'thead a', function() {
		var val = $(this).attr('data-val');
		conflicts.find('input[type=radio]').each(function() {
			this.checked = $(this).val() == val;
		});
		return false;
	});
})();
ENDJS
);
