<div class="form-content">

	<?php $form = $this->beginWidget('ActiveForm', array(
		'action' => Yii::app()->createUrl($this->route),
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'role' => 'search-form',
			'data-target' => 'completion-report-grid',
		),
		'method' => 'get',
	)); ?>
		<div class="form-group">
			<?php echo $form->label($model, 'performer_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'performer_id', null, array(
					'ajax' => array(
						'url' => $this->createUrl('user/query'),
					),
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'contragent_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->tagField($model, 'contragent_id', null, array(
					'ajax' => array(
						'url' => $this->createUrl('user/query'),
					),
				)); ?> 
			</div>
		</div>
		<div class="form-group">
			<?php echo $form->label($model, 'contract_number', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->textField($model, 'contract_number', array(
					'class' => 'form-control',
				)); ?> 
			</div>
		</div>
		
		<div class="form-group">
			<div class="col-sm-offset-3 col-sm-9">
				<?php echo CHtml::submitButton(Yii::t('core.crud', 'Search'), array('class'=>'btn btn-default')); ?>
			</div>
		</div>

	<?php $this->endWidget(); ?>

</div>
