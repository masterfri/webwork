<div id="tasklist" class="tasklist-container" data-onload="tasklist.init">
	<ul class="schedule-tasklist">
		<?php foreach ($tasks->getData() as $task): ?>
			<li class="task" data-task="<?php echo $task->id; ?>">
				<div class="title">
					<?php echo CHtml::link(CHtml::encode($task->name), array('task/view', 'id' => $task->id), array('target' => '_blank', 'title' => $task->name)); ?>
				</div>
				<div class="task-details">
					<div class="priority">
						<span class="glyphicon glyphicon-arrow-up" title="<?php echo Yii::t('task', 'Priority'); ?>"></span>
						<?php echo ViewHelper::taskPriorityLabel($task->priority); ?>
					</div>
					<div class="due-date">
						<span class="glyphicon glyphicon-fire" title="<?php echo Yii::t('task', 'Due Date'); ?>"></span>
						<?php if (!MysqlDateHelper::isEmpty($task->due_date)): ?>
							<?php echo Yii::app()->format->formatDate($task->due_date); ?>
						<?php else: ?>
							<span class="not-set"><?php echo Yii::t('core.crud', 'Not set'); ?></span>
						<?php endif; ?>
					</div>
					<div class="estimate">
						<span class="glyphicon glyphicon-time" title="<?php echo Yii::t('task', 'Estimate'); ?>"></span>
						<?php echo ViewHelper::formatEstimate($task->getEstimateRange()); ?>
					</div>
				</div>
			</li>
		<?php endforeach; ?>
		<li class="drop-to-remove"><?php echo Yii::t('core.crud', 'Drop here to remove'); ?></li>
	</ul>
	<?php if($tasks->pagination->pageCount > 1): ?>
		<div class="paginate">
			<div class="row">
				<div class="col-xs-6 prev">
					<?php if ($tasks->pagination->currentPage > 0): ?>
						<a data-ajax-update="#tasklist" href="<?php echo $this->createUrl('tasklist', array('Task_page' => $tasks->pagination->currentPage, 'project' => $project->id)); ?>">&larr;</a>
					<?php endif; ?>
				</div>
				<div class="col-xs-6 next">
					<?php if ($tasks->pagination->currentPage + 1 < $tasks->pagination->pageCount): ?>
						<a data-ajax-update="#tasklist" href="<?php echo $this->createUrl('tasklist', array('Task_page' => $tasks->pagination->currentPage + 2, 'project' => $project->id)); ?>">&rarr;</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
