<?php

$this->pageHeading = Yii::t('core.crud', 'General Options');

$this->breadcrumbs = array(
	Yii::t('core.crud', 'General Options'),
);

?>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $this->pageHeading; ?></h3>
	</div>
	<div class="panel-body">	
		<div class="form-content">
			<?php $form=$this->beginWidget('ActiveForm', array(
				'id' => 'generaloptions-form',
				'htmlOptions' => array(
					'class'=>'form-horizontal',
				),
				'enableClientValidation' => true,
				'clientOptions' => array(
					'validateOnSubmit' => true,
					'afterValidate' => 'js:function(f,d,e) {
						if (e) $("html, body").animate({scrollTop: $("#generaloptions-form").offset().top - 50}, 1000);
						return true;
					}',
				),
			)); ?>
			
			<?php echo $form->errorSummary($model, null, null, array('class' => 'alert alert-danger')); ?>
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<h4><?php echo Yii::t('core.crud', 'Applications options'); ?></h4>
				</div>
			</div>

			<div class="form-group">
				<?php echo $form->labelEx($model, 'app_domain', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-9">
					<?php echo $form->textField($model, 'app_domain', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'app_domain', array('class'=>'help-inline')); ?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<h4><?php echo Yii::t('core.crud', 'Task estimating'); ?></h4>
				</div>
			</div>

			<div class="form-group">
				<?php echo $form->labelEx($model, 'complexity_rate', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-3">
					<?php echo $form->textField($model, 'complexity_rate', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'complexity_rate', array('class'=>'help-inline')); ?>
				</div>
				<?php echo $form->labelEx($model, 'estimate_error_rate', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-3">
					<?php echo $form->textField($model, 'estimate_error_rate', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'estimate_error_rate', array('class'=>'help-inline')); ?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<h4><?php echo Yii::t('core.crud', 'HttpSh Options'); ?></h4>
				</div>
			</div>
			
			<div class="form-group">
				<?php echo $form->labelEx($model, 'httpsh_host', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-3">
					<?php echo $form->textField($model, 'httpsh_host', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'httpsh_host', array('class'=>'help-inline')); ?>
				</div>
				<?php echo $form->labelEx($model, 'httpsh_port', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-3">
					<?php echo $form->textField($model, 'httpsh_port', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'httpsh_port', array('class'=>'help-inline')); ?>
				</div>
			</div>
			
			<div class="form-group">
				<?php echo $form->labelEx($model, 'httpsh_login', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-3">
					<?php echo $form->textField($model, 'httpsh_login', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'httpsh_login', array('class'=>'help-inline')); ?>
				</div>
				<?php echo $form->labelEx($model, 'httpsh_password', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-3">
					<?php echo $form->textField($model, 'httpsh_password', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'httpsh_password', array('class'=>'help-inline')); ?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<h4><?php echo Yii::t('core.crud', 'Database management'); ?></h4>
				</div>
			</div>

			<div class="form-group">
				<?php echo $form->labelEx($model, 'phpmyadmin_url', array('class'=>'col-sm-3 control-label')); ?>
				<div class="col-sm-9">
					<?php echo $form->textField($model, 'phpmyadmin_url', array(
						'class' => 'form-control',
					)); ?> 
					<?php echo $form->error($model, 'phpmyadmin_url', array('class'=>'help-inline')); ?>
				</div>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<?php echo CHtml::submitButton(Yii::t('core.crud', 'Update'), array('class'=>'btn btn-primary')); ?>
				</div>
			</div>

			<?php $this->endWidget(); ?>
		</div>
	</div>
</div>
