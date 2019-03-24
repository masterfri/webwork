<div class="form-content">

	<?php $form = $this->beginWidget('CActiveForm', array(
		'action' => Yii::app()->createUrl($this->route),
		'htmlOptions' => array(
			'class'=>'form-horizontal',
			'role' => 'search-form',
			'data-target' => 'question-grid',
		),
		'method' => 'get',
	)); ?>
	
		<div class="form-group">
			<?php echo $form->label($model, 'text', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->textField($model, 'text', array('class'=>'form-control')); ?>
			</div>
		</div>
		
		<div class="form-group">
			<?php echo $form->label($model, 'category_id', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'category_id', QuestionCategory::getList(), array(
					'class' => 'form-control',
					'prompt' => '',
				)); ?> 
			</div>
		</div>
		
		<div class="form-group">
			<?php echo $form->label($model, 'level', array('class'=>'col-sm-3 control-label')); ?>
			<div class="col-sm-9">
				<?php echo $form->dropdownList($model, 'level', Question::getListLevels(), array(
					'class' => 'form-control',
					'prompt' => '',
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
