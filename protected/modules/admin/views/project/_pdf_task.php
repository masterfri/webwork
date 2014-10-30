<h4><?php echo CHtml::encode($task->name); ?></h4>
<table style="color: #777; width: 100%;">
	<tr>
		<td style="width: 40%;">
			<?php echo Yii::t('task', 'Estimate'); ?>:
			<?php echo ViewHelper::formatEstimate($task->getEstimateRange()); ?>
		</td>
		<td style="width: 30%;">
			<?php echo Yii::t('task', 'Date Scheduled'); ?>:
			<?php if (!empty($task->date_sheduled) && '0000-00-00' != $task->date_sheduled): ?>
				<?php echo Yii::app()->format->formatDate($task->date_sheduled); ?>
			<?php else: ?>
				<span class="not-set"><?php echo Yii::t('admin.crud', 'Not set'); ?></span>
			<?php endif; ?>
		</td>
		<td style="width: 30%;">
			<?php echo Yii::t('task', 'Due Date'); ?>:
			<?php if (!empty($task->due_date) && '0000-00-00' != $task->due_date): ?>
				<?php echo Yii::app()->format->formatDate($task->due_date); ?>
			<?php else: ?>
				<span class="not-set"><?php echo Yii::t('admin.crud', 'Not set'); ?></span>
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
