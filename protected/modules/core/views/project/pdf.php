<?php $this->beginWidget('RenderPdf'); ?>
<style>
	<?php $this->renderPartial('../layouts/include/pdf-style'); ?>
	table td {font-size: 90%; padding: 0 0 3mm 0;}
</style>
<page style="font-family:dejavusans;">
	<?php $this->renderPartial('../layouts/include/pdf-header'); ?>
	<div style="text-align: center; padding-top: 100mm;">
		<h1><?php echo CHtml::encode($model->name); ?></h1>
		<h3 style="color:#777;"><?php echo Yii::t('core.crud', 'Technical specification of project'); ?></h3>
	</div>
</page>
<page style="font-family:dejavusans;" backtop="10mm" backbottom="10mm">
	<?php $this->renderPartial('../layouts/include/pdf-header'); ?>
	<?php if ('' != $model->scope): ?>
		<h2><?php echo Yii::t('project', 'Scope'); ?></h2>
		<?php 
			$this->beginWidget('MarkdownWidget'); 
			echo $model->scope;
			$this->endWidget(); 
		?>
	<?php endif; ?>
	<?php 
		$estimate_min = 0;
		$estimate_max = 0;
		$work_start = '';
		$max_start = '';
		$work_finish = '';
	?>
	<?php if(count($tasks = $model->getTasks(array('condition' => 'milestone_id = 0')))): ?>
		<h2><?php echo Yii::t('core.crud', 'General tasks'); ?></h2>
		<?php foreach($tasks as $task): ?>
			<?php $this->renderPartial('_pdf_task', array(
				'task' => $task,
			)); ?>
			<?php 
				list($min, $max) = $task->getEstimateRange();
				$estimate_min += $min;
				$estimate_max += $max;
				if (!MysqlDateHelper::isEmpty($task->date_sheduled)) {
					if (MysqlDateHelper::isEmpty($work_start) || MysqlDateHelper::lt($task->date_sheduled, $work_start)) {
						$work_start = $task->date_sheduled;
					}
					if (MysqlDateHelper::isEmpty($max_start) || MysqlDateHelper::gt($task->date_sheduled, $max_start)) {
						$max_start = $task->date_sheduled;
					}
				}
				if (!MysqlDateHelper::isEmpty($task->due_date)) {
					if (MysqlDateHelper::isEmpty($work_finish) || MysqlDateHelper::gt($task->due_date, $work_finish)) {
						$work_finish = $task->due_date;
					}
				}
			?>
		<?php endforeach; ?>
	<?php endif; ?>
	<?php foreach ($model->getMilestones() as $milestone): ?>
		<h2><?php echo CHtml::encode($milestone->name); ?></h2>
		<table style="color: #777; width: 100%;">
			<tr>
				<td style="width: 34%;">
					<?php echo Yii::t('milestone', 'Due Date'); ?>:
					<?php if (!MysqlDateHelper::isEmpty($milestone->due_date)): ?>
						<?php echo Yii::app()->format->formatDate($milestone->due_date); ?>
					<?php else: ?>
						<span class="not-set"><?php echo Yii::t('core.crud', 'Not set'); ?></span>
					<?php endif; ?>
				</td>
			</tr>
		</table>
		<?php if ('' != $milestone->description): ?>
			<?php 
				$this->beginWidget('MarkdownWidget'); 
				echo $milestone->description;
				$this->endWidget(); 
			?>
		<?php endif; ?>
		<?php foreach($milestone->getTasks() as $task): ?>
			<?php $this->renderPartial('_pdf_task', array(
				'task' => $task,
			)); ?>
			<?php 
				list($min, $max) = $task->getEstimateRange();
				$estimate_min += $min;
				$estimate_max += $max;
				if (!MysqlDateHelper::isEmpty($task->date_sheduled)) {
					if (MysqlDateHelper::isEmpty($work_start) || MysqlDateHelper::lt($task->date_sheduled, $work_start)) {
						$work_start = $task->date_sheduled;
					}
				}
				if (!MysqlDateHelper::isEmpty($task->due_date)) {
					if (MysqlDateHelper::isEmpty($work_finish) || MysqlDateHelper::gt($task->due_date, $work_finish)) {
						$work_finish = $task->due_date;
					}
				}
			?>
		<?php endforeach; ?>
	<?php endforeach; ?>
	<h2><?php echo Yii::t('core.crud', 'Summary'); ?></h2>
	<p>
		<?php echo Yii::t('core.crud', 'Total estimate'); ?>: 
		<?php echo ViewHelper::formatEstimate(array($estimate_min, $estimate_max)); ?>
	</p>
	<p>
		<?php echo Yii::t('core.crud', 'Work start date'); ?>: 
		<?php if (!MysqlDateHelper::isEmpty($work_start)): ?>
			<?php echo Yii::app()->format->formatDate($work_start); ?>
		<?php else: ?>
			<span class="not-set"><?php echo Yii::t('core.crud', 'Not set'); ?></span>
		<?php endif; ?>
	</p>
	<p>
		<?php echo Yii::t('core.crud', 'Work finish date'); ?>: 
		<?php if (!MysqlDateHelper::isEmpty($work_finish) && !MysqlDateHelper::lt($work_finish, $max_start)): ?>
			<?php echo Yii::app()->format->formatDate($work_finish); ?>
		<?php else: ?>
			<span class="not-set"><?php echo Yii::t('core.crud', 'Not set'); ?></span>
		<?php endif; ?>
	</p>
	<?php if (count($model->assignments)): ?>
		<h2><?php echo Yii::t('project', 'Responsible Persons'); ?></h2>
		<ul>
			<?php foreach ($model->assignments as $member): ?>
				<li>
					<?php echo CHtml::encode($member->user); ?> (<?php echo CHtml::encode($member->getRoleName()); ?>)
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</page>
<?php $this->endWidget(); ?>
