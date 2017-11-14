<div class="sticky-note col-sm-6 col-lg-3">
	<div class="panel <?php echo $data->private ? 'panel-success' : 'panel-default'; ?>">
		<div class="panel-heading">
			<em>
				<?php echo CHtml::encode($data->created_by); ?>,
				<?php echo Yii::app()->format->formatDatetime($data->time_created); ?> 
			</em>
			<div class="actions">
				<?php if(Yii::app()->user->checkAccess('update_note', array('note' => $data))): ?>
					<a href="<?php echo $this->createUrl('note/update', array('id' => $data->id)); ?>" data-raise="ajax-modal"><span class="glyphicon glyphicon-pencil"></span></a>
				<?php endif; ?>
				<?php if(Yii::app()->user->checkAccess('delete_note', array('note' => $data))): ?>
					<a href="<?php echo $this->createUrl('note/delete', array('id' => $data->id)); ?>" data-raise="ajax-request" data-method="POST" data-confirmation="<?php echo Yii::t('core.crud', 'Are you sure you want to delete this note?')?>"><span class="glyphicon glyphicon-remove"></span></a>
				<?php endif; ?>
			</div>
		</div>
		<div class="panel-body">
			<div class="text"><?php echo nl2br(CHtml::encode($data->text)); ?></div>
			<div class="fadebox"></div>
		</div>
	</div>
</div>
