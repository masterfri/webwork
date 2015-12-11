<?php

$this->pageHeading = Yii::t('core.crud', 'Build Application');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'Projects') => Yii::app()->user->checkAccess('view_project') ? array('project/index') : false, 
	CHtml::encode($application->project->name) => Yii::app()->user->checkAccess('view_project', array('project' => $application->project)) ? array('project/view', 'id' => $application->project->id) : false, 
	CHtml::encode($application->name) => Yii::app()->user->checkAccess('view_application', array('application' => $application)) ? array('application/view', 'id' => $application->id) : false, 
	Yii::t('core.crud', 'Build')
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
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">
		<?php $form=$this->beginWidget('ActiveForm', array(
				'id' => 'build-form',
				'htmlOptions' => array(
					'class'=>'form-horizontal',
				),
				'enableClientValidation' => true,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'afterValidate' => 'js:function(f,d,e) {
						if (e) {
							$("html, body").animate({scrollTop: $("#build-form").offset().top - 50}, 1000);
						} else {
							f.children(".preloader").show();
							f.children(".options").hide();
							setTimeout(function() { f.find(".btn").attr("disabled", "disabled"); }, 50);
						}
						return true;
					}',
				),
			)); ?>
			
			<?php echo $form->errorSummary($application, null, null, array('class' => 'alert alert-danger')); ?>
			
			<div class="options">
				<div class="form-group">
					<?php echo $form->labelEx($application, 'entitiesToBuild', array('class'=>'col-sm-3 control-label')); ?>
					<div class="col-sm-9">
						<div class="checkbox">
							<?php echo $form->checkboxList($application, 'entitiesToBuild', $entities); ?> 
						</div>
						<?php echo $form->error($application, 'entitiesToBuild', array('class'=>'help-inline')); ?>
					</div>
				</div>
				
				<div class="form-group">
					<?php echo $form->labelEx($application, 'schemesToBuild', array('class'=>'col-sm-3 control-label')); ?>
					<div class="col-sm-9">
						<div class="checkbox">
							<?php echo $form->checkboxList($application, 'schemesToBuild', $schemes); ?> 
						</div>
						<?php echo $form->error($application, 'schemesToBuild', array('class'=>'help-inline')); ?>
					</div>
				</div>
				
				<div class="form-group">
					<?php echo $form->labelEx($application, 'packages', array('class'=>'col-sm-3 control-label')); ?>
					<div class="col-sm-9">
						<div class="checkbox" id="packages-list">
							<?php $i = 0; $p = $application->getPackages();
								foreach ($packages as $name => $deps): 
								$checked = in_array($name, $p); ?>
								<?php echo CHtml::checkbox('Application[packages][]', $checked, array(
									'value' => $name,
									'id' => 'Application_packages_' . $i,
									'data-deps' => implode(' ', $deps['deps']),
									'data-opts' => implode(' ', $deps['opts']),
									'disabled' => $checked,
								)); ?> 
								<label for="Application_packages_<?php echo $i++; ?>"><?php echo CHtml::encode($name); ?></label><br />
								<?php if ($checked): ?>
									<input type="hidden" name="Application[packages][]" value="<?php echo CHtml::encode($name); ?>" />
								<?php endif; ?>
							<?php endforeach; ?>
						</div>
						<?php echo $form->error($application, 'packages', array('class'=>'help-inline')); ?>
					</div>
				</div>
				
				<div class="form-group">
					<?php echo $form->labelEx($application, 'buildOptionsFlat', array('class'=>'col-sm-3 control-label')); ?>
					<div class="col-sm-9">
						<?php echo $form->textArea($application, 'buildOptionsFlat', array(
							'class' => 'form-control',
						)); ?> 
						<?php echo $form->error($application, 'buildOptionsFlat', array('class'=>'help-inline')); ?>
						<p class="help-block"><?php echo Yii::t('core.crud', 'One option per line, separate option name and option value by space'); ?></p>
					</div>
				</div>
			</div>
			
			<div style="display: none;" class="preloader">
				<p><?php echo Yii::t('core.crud', 'Operation in process... Please, be patient.'); ?></p>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<?php echo CHtml::submitButton(Yii::t('core.crud', 'Build'), array('class'=>'btn btn-primary')); ?>
				</div>
			</div>
			
		<?php $this->endWidget(); ?>
	</div>
</div>

<?php

$missdep = Yii::t('core.crud', 'Required package is missing');
Yii::app()->clientScript->registerScript('deps',
<<<ENDJS
function setDeps(deps, opts) {
	var d = [];
	if (deps) d = d.concat(deps.split(' '));
	if (opts) d = d.concat(opts.split(' '));
	var list = $('#packages-list input[type=checkbox]');
	d.forEach(function(i) {
		var ch = list.filter('[value="' + i + '"]');
		if (ch.length == 0) {
			alert('$missdep: ' + i);
		} else if (!ch.get(0).checked) {
			ch.get(0).checked = true;
			setDeps(ch.attr('data-deps'), ch.attr('data-opts'));
		}
	});
}
function unsetDeps(pkg) {
	var list = $('#packages-list input[type=checkbox]');
	list.each(function() {
		var d = this.getAttribute('data-deps');
		if (d && this.checked) {
			if (d.split(' ').indexOf(pkg) != -1) {
				this.checked = false;
				unsetDeps(this.getAttribute('value'));
			}
		}
	});
}
$('#packages-list').on('change', 'input[type=checkbox]', function() {
	if (this.checked) {
		setDeps(this.getAttribute('data-deps'), this.getAttribute('data-opts'));
	} else {
		unsetDeps(this.getAttribute('value'));
	}
});
ENDJS
);
