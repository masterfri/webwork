<h4><?php echo CHtml::encode($task->name); ?></h4>
<table style="color: #777; width: 100%;">
	<tr>
		<td style="width: 40%;">
			<?php echo Yii::t('task', 'Estimate'); ?>:
			<?php echo ViewHelper::formatEstimate($task->getEstimateRange()); ?>
		</td>
		<td style="width: 30%;">
			<?php echo Yii::t('task', 'Date Scheduled'); ?>:
			<?php if (!MysqlDateHelper::isEmpty($task->date_sheduled)): ?>
				<?php echo Yii::app()->format->formatDate($task->date_sheduled); ?>
			<?php else: ?>
				<span class="not-set"><?php echo Yii::t('core.crud', 'Not set'); ?></span>
			<?php endif; ?>
		</td>
		<td style="width: 30%;">
			<?php echo Yii::t('task', 'Due Date'); ?>:
			<?php if (!MysqlDateHelper::isEmpty($task->due_date)): ?>
				<?php echo Yii::app()->format->formatDate($task->due_date); ?>
			<?php else: ?>
				<span class="not-set"><?php echo Yii::t('core.crud', 'Not set'); ?></span>
			<?php endif; ?>
		</td>
	</tr>
</table>
<?php if ('' != $task->description): ?>
	<?php 
		$this->beginWidget('CMarkdown'); 
		echo $task->description;
		$this->endWidget(); 
	?>
<?php endif; ?>
