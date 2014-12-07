<div class="task-item task-phase-<?php echo $data->phase; ?>">
	<div class="row">
		<div class="col-sm-6">
			<?php echo ViewHelper::taskPhaseIcon($data->phase); ?>
			<?php echo CHtml::link(CHtml::encode($data->name), array('task/view', 'id' => $data->id)); ?>
			<?php if(Yii::app()->user->checkAccess('view_project', array('project' => $data->project))): ?>
				:: 
				<?php echo CHtml::link(CHtml::encode($data->project->name), array('project/view', 'id' => $data->project->id)); ?>
			<?php endif; ?>
			<?php if($data->milestone && Yii::app()->user->checkAccess('view_milestone', array('milestone' => $data->milestone))): ?>
				<small class="milestone">(<span class="glyphicon glyphicon-calendar"></span>
				<?php echo CHtml::link(CHtml::encode($data->milestone->name), array('milestone/view', 'id' => $data->milestone->id)); ?>)
				</small>
			<?php endif; ?>
			<?php echo ViewHelper::listTags($data->tags, array('class' => 'tags')); ?>
		</div>
		<div class="col-sm-6 task-details">
			<div class="row">
				<div class="col-xs-6">
					<div class="assigned task-detail">
						<span class="l glyphicon glyphicon-user" title="<?php echo Yii::t('task', 'Assigned'); ?>"></span>
						<?php if ($data->assigned): ?>
							<?php echo CHtml::encode($data->assigned); ?>
						<?php else: ?>
							<span class="not-set"><?php echo Yii::t('admin.crud', 'Nobody'); ?></span>
						<?php endif; ?>
					</div>
					<div class="date-scheduled task-detail">
						<span class="l glyphicon glyphicon-calendar" title="<?php echo Yii::t('task', 'Date Scheduled'); ?>"></span>
						<?php if (!MysqlDateHelper::isEmpty($data->date_sheduled)): ?>
							<?php echo Yii::app()->format->formatDate($data->date_sheduled); ?>
						<?php else: ?>
							<span class="not-set"><?php echo Yii::t('admin.crud', 'Not set'); ?></span>
						<?php endif; ?>
					</div>
				</div>
				<div class="col-xs-6">
					<div class="priority task-detail">
						<span class="l glyphicon glyphicon-arrow-up" title="<?php echo Yii::t('task', 'Priority'); ?>"></span>
						<?php echo ViewHelper::taskPriorityLabel($data->priority); ?>
					</div>
					<div class="due-date task-detail">
						<span class="l glyphicon glyphicon-fire" title="<?php echo Yii::t('task', 'Due Date'); ?>"></span>
						<?php if (!MysqlDateHelper::isEmpty($data->due_date)): ?>
							<?php echo Yii::app()->format->formatDate($data->due_date); ?>
						<?php else: ?>
							<span class="not-set"><?php echo Yii::t('admin.crud', 'Not set'); ?></span>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
